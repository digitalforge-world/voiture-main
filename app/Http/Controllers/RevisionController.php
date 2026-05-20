<?php

namespace App\Http\Controllers;

use App\Models\Revision;
use App\Models\RevisionConversation;
use App\Services\AIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RevisionController extends Controller
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function create()
    {
        return view('revisions.create');
    }

    /**
     * Start a new AI chat discussion thread
     */
    public function startChat(Request $request)
    {
        $existingId = $request->input('conversation_id');

        if ($existingId && Str::isUuid($existingId)) {
            $conversation = RevisionConversation::where('id', $existingId)->first();
            if ($conversation && !$conversation->is_closed) {
                return response()->json([
                    'success' => true,
                    'conversation_id' => $conversation->id,
                    'messages' => $conversation->messages,
                    'is_closed' => false,
                    'metadata' => [
                        'client_nom' => $conversation->client_nom,
                        'client_telephone' => $conversation->client_telephone,
                        'marque_vehicule' => $conversation->marque_vehicule,
                        'modele_vehicule' => $conversation->modele_vehicule,
                    ]
                ]);
            }
        }

        $uuid = (string) Str::uuid();

        // Welcome message from the AI
        $welcomeMessage = [
            'role' => 'assistant',
            'content' => "Bonjour ! Bienvenue à l'atelier de précision AutoImport Hub. Je suis votre conseiller technique intelligent. Dites-moi, quel problème mécanique ou d'entretien rencontrez-vous sur votre véhicule aujourd'hui ?",
            'timestamp' => now()->toISOString()
        ];

        $conversation = RevisionConversation::create([
            'id' => $uuid,
            'messages' => [$welcomeMessage],
            'is_closed' => false
        ]);

        return response()->json([
            'success' => true,
            'conversation_id' => $uuid,
            'messages' => $conversation->messages
        ]);
    }

    /**
     * Handle incoming user message, call AI service, and save state
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|uuid',
            'message' => 'nullable|string|max:1000',
            'image' => 'nullable|string'
        ]);

        $conversation = RevisionConversation::findOrFail($request->conversation_id);

        if ($conversation->is_closed) {
            return response()->json([
                'success' => false,
                'message' => 'Cette discussion est clôturée.'
            ], 400);
        }

        // 1. Append User Message
        $messages = $conversation->messages ?? [];
        $userText = $request->message ?: "Image de la panne";
        
        $userMessage = [
            'role' => 'user',
            'content' => $userText,
            'timestamp' => now()->toISOString()
        ];

        if ($request->filled('image')) {
            $userMessage['image'] = $request->image;
        }

        $messages[] = $userMessage;

        // 2. Call AI Service (automatically handles Gemini / Groq / Fallback)
        $aiResponse = $this->aiService->getResponse($messages);

        // 3. Process AI Response & Metadata
        $aiText = $aiResponse['message'] ?? "Une erreur est survenue.";
        $metadata = $aiResponse['metadata'] ?? [];

        // 4. Append AI Message
        $messages[] = [
            'role' => 'assistant',
            'content' => $aiText,
            'timestamp' => now()->toISOString()
        ];

        // 5. Update Conversation Record with metadata
        $conversation->messages = $messages;
        if (!empty($metadata['client_nom'])) $conversation->client_nom = $metadata['client_nom'];
        if (!empty($metadata['client_telephone'])) $conversation->client_telephone = $metadata['client_telephone'];
        if (!empty($metadata['client_email'])) $conversation->client_email = $metadata['client_email'];
        if (!empty($metadata['marque_vehicule'])) $conversation->marque_vehicule = $metadata['marque_vehicule'];
        if (!empty($metadata['modele_vehicule'])) $conversation->modele_vehicule = $metadata['modele_vehicule'];
        if (!empty($metadata['annee_vehicule'])) $conversation->annee_vehicule = $metadata['annee_vehicule'];
        if (!empty($metadata['summary'])) $conversation->summary = $metadata['summary'];

        $trackingNumber = null;

        // 6. Check for Closing & Automated Creation
        if (!empty($metadata['is_closed']) && $metadata['is_closed'] == true) {
            // Verify we have phone & model, if not we override fallback or use standard defaults
            $clientNom = $conversation->client_nom ?? 'Client Anonyme';
            $clientTel = $conversation->client_telephone ?? '00000000';
            $clientEmail = $conversation->client_email ?? null;
            $marque = $conversation->marque_vehicule ?? 'Non spécifiée';
            $modele = $conversation->modele_vehicule ?? 'Non spécifié';
            $annee = $conversation->annee_vehicule ?? date('Y');
            $desc = $conversation->summary ?? $request->message;

            // Generate tracking number
            $trackingNumber = \App\Helpers\TrackingHelper::forRevision();

            $revision = Revision::create([
                'user_id' => Auth::id(),
                'marque_vehicule' => $marque,
                'modele_vehicule' => $modele,
                'annee_vehicule' => $annee,
                'probleme_description' => $desc,
                'type_revision' => 'complete',
                'statut' => 'en_attente',
                'reference' => 'REV-' . strtoupper(Str::random(8)),
                'tracking_number' => $trackingNumber,
                'client_nom' => $clientNom,
                'client_telephone' => $clientTel,
                'client_email' => $clientEmail,
            ]);

            $conversation->is_closed = true;
            $conversation->revision_id = $revision->id;
        }

        $conversation->save();

        return response()->json([
            'success' => true,
            'message' => $aiText,
            'is_closed' => $conversation->is_closed,
            'tracking_number' => $trackingNumber,
            'metadata' => [
                'client_nom' => $conversation->client_nom,
                'client_telephone' => $conversation->client_telephone,
                'marque_vehicule' => $conversation->marque_vehicule,
                'modele_vehicule' => $conversation->modele_vehicule,
            ]
        ]);
    }

    /**
     * Manual closing action from form fallback
     */
    public function closeChat(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|uuid',
            'client_nom' => 'required|string|max:255',
            'client_telephone' => 'required|string|max:20',
            'client_email' => 'nullable|email|max:255',
        ]);

        $conversation = RevisionConversation::findOrFail($request->conversation_id);

        if ($conversation->is_closed) {
            return response()->json([
                'success' => false,
                'message' => 'Cette discussion est déjà clôturée.'
            ], 400);
        }

        // Update fields manually
        $conversation->client_nom = $request->client_nom;
        $conversation->client_telephone = $request->client_telephone;
        if ($request->filled('client_email')) {
            $conversation->client_email = $request->client_email;
        }

        $trackingNumber = \App\Helpers\TrackingHelper::forRevision();

        $revision = Revision::create([
            'user_id' => Auth::id(),
            'marque_vehicule' => $conversation->marque_vehicule ?? 'Non spécifiée',
            'modele_vehicule' => $conversation->modele_vehicule ?? 'Non spécifié',
            'annee_vehicule' => $conversation->annee_vehicule ?? date('Y'),
            'probleme_description' => $conversation->summary ?? 'Demande de révision via Assistant IA',
            'type_revision' => 'complete',
            'statut' => 'en_attente',
            'reference' => 'REV-' . strtoupper(Str::random(8)),
            'tracking_number' => $trackingNumber,
            'client_nom' => $conversation->client_nom,
            'client_telephone' => $conversation->client_telephone,
            'client_email' => $conversation->client_email,
        ]);

        $conversation->is_closed = true;
        $conversation->revision_id = $revision->id;
        $conversation->save();

        return response()->json([
            'success' => true,
            'tracking_number' => $trackingNumber
        ]);
    }

    /**
     * Original standard store method (kept as fallback compatibility)
     */
    public function store(Request $request)
    {
        $request->validate([
            'marque_vehicule' => 'required',
            'modele_vehicule' => 'required',
            'annee_vehicule' => 'required',
            'probleme_description' => 'required',
            'client_nom' => 'required|string|max:255',
            'client_telephone' => 'required|string|max:20',
        ]);

        $trackingNumber = \App\Helpers\TrackingHelper::forRevision();

        Revision::create([
            'user_id' => Auth::id(),
            'marque_vehicule' => $request->marque_vehicule,
            'modele_vehicule' => $request->modele_vehicule,
            'annee_vehicule' => $request->annee_vehicule,
            'immatriculation' => $request->immatriculation,
            'kilometrage' => $request->kilometrage,
            'probleme_description' => $request->probleme_description,
            'type_revision' => $request->type_revision ?? 'complete',
            'statut' => 'en_attente',
            'reference' => 'REV-' . strtoupper(Str::random(8)),
            'tracking_number' => $trackingNumber,
            'client_nom' => $request->client_nom,
            'client_telephone' => $request->client_telephone,
            'client_email' => $request->client_email,
        ]);

        return redirect()->route('tracking.success')->with('tracking_number', $trackingNumber);
    }
}

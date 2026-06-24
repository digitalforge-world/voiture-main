<?php

namespace App\Http\Controllers;

use App\Models\ReservationTransport;
use App\Models\TransportConversation;
use App\Models\Notification;
use App\Models\User;
use App\Helpers\TrackingHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TransportController extends Controller
{
    /**
     * Page publique de réservation transport.
     */
    public function index()
    {
        return view('transport.index');
    }

    /**
     * Créer une nouvelle réservation transport.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_nom'           => 'required|string|max:255',
            'client_telephone'     => 'required|string|max:25',
            'client_email'         => 'nullable|email|max:255',
            'lieu_depart'          => 'required|string|max:500',
            'lieu_arrivee'         => 'required|string|max:500',
            'lat_depart'           => 'nullable|numeric',
            'lng_depart'           => 'nullable|numeric',
            'lat_arrivee'          => 'nullable|numeric',
            'lng_arrivee'          => 'nullable|numeric',
            'date_prise_en_charge' => 'required|date|after:now',
            'nombre_personnes'     => 'required|integer|min:1|max:20',
            'type_service'         => 'required|in:aeroport,gare,evenement,course,autre',
            'notes_client'         => 'nullable|string|max:1000',
        ]);

        $trackingNumber = TrackingHelper::generate('TRS');
        $reference      = 'TRS-' . strtoupper(Str::random(8));
        $driverToken    = Str::random(32);

        $reservation = ReservationTransport::create(array_merge($validated, [
            'reference'      => $reference,
            'tracking_number'=> $trackingNumber,
            'driver_token'   => $driverToken,
            'statut'         => 'en_attente',
        ]));

        // Message de bienvenue automatique dans le chat
        TransportConversation::create([
            'reservation_transport_id' => $reservation->id,
            'auteur'  => 'systeme',
            'message' => "✅ Votre réservation a bien été enregistrée. Notre équipe va vous contacter sous peu pour confirmer les détails et le tarif.",
            'type'    => 'notification_systeme',
        ]);

        // Notifier tous les admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'utilisateur_id' => $admin->id,
                'type'    => 'transport',
                'titre'   => '🚗 Nouvelle réservation transport',
                'message' => "Client : {$validated['client_nom']} — {$validated['lieu_depart']} → {$validated['lieu_arrivee']}",
                'lien'    => "/admin/transport/{$reservation->id}",
                'lu'      => false,
            ]);
        }

        return redirect()->route('transport.suivi', $trackingNumber)
            ->with('success', 'Réservation créée ! Vous pouvez suivre son état ici.');
    }

    /**
     * Page de suivi client (chat + carte + tracking chauffeur).
     */
    public function suivi(string $tracking)
    {
        $reservation = ReservationTransport::where('tracking_number', $tracking)->firstOrFail();
        return view('transport.suivi', compact('reservation'));
    }

    /**
     * Envoi d'un message depuis le client.
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'tracking' => 'required|string',
            'message'  => 'required|string|max:1000',
        ]);

        $reservation = ReservationTransport::where('tracking_number', $request->tracking)->firstOrFail();

        $msg = TransportConversation::create([
            'reservation_transport_id' => $reservation->id,
            'auteur'  => 'client',
            'message' => $request->message,
            'type'    => 'message',
        ]);

        return response()->json(['success' => true, 'message' => $msg]);
    }

    /**
     * Client accepte le prix proposé par l'admin.
     */
    public function acceptPrice(Request $request)
    {
        $request->validate(['tracking' => 'required|string']);

        $reservation = ReservationTransport::where('tracking_number', $request->tracking)->firstOrFail();

        if (!$reservation->prix_propose) {
            return response()->json(['success' => false, 'message' => 'Aucun prix proposé.'], 422);
        }

        $reservation->update(['prix_accepte' => true]);

        TransportConversation::create([
            'reservation_transport_id' => $reservation->id,
            'auteur'  => 'client',
            'message' => "✅ J'accepte le prix proposé de " . number_format($reservation->prix_propose, 0, ',', ' ') . " FCFA.",
            'type'    => 'confirmation_prix',
            'montant' => $reservation->prix_propose,
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Polling Ajax : retourne les nouveaux messages du chat.
     */
    public function getMessages(Request $request, string $tracking)
    {
        $reservation = ReservationTransport::where('tracking_number', $tracking)->firstOrFail();

        $sinceId = $request->get('since_id', 0);
        $messages = TransportConversation::where('reservation_transport_id', $reservation->id)
            ->where('id', '>', $sinceId)
            ->orderBy('created_at')
            ->get();

        return response()->json([
            'messages'         => $messages,
            'statut'           => $reservation->statut,
            'prix_propose'     => $reservation->prix_propose,
            'prix_accepte'     => $reservation->prix_accepte,
        ]);
    }

    /**
     * Polling Ajax : retourne la position GPS actuelle du chauffeur.
     */
    public function getDriverLocation(string $tracking)
    {
        $reservation = ReservationTransport::where('tracking_number', $tracking)
            ->select(['statut', 'chauffeur_lat', 'chauffeur_lng', 'chauffeur_arrived', 'chauffeur_arrived_at'])
            ->firstOrFail();

        return response()->json([
            'trackable'        => $reservation->isDriverTrackable(),
            'chauffeur_lat'    => $reservation->chauffeur_lat,
            'chauffeur_lng'    => $reservation->chauffeur_lng,
            'chauffeur_arrived'=> $reservation->chauffeur_arrived,
            'statut'           => $reservation->statut,
        ]);
    }

    /**
     * Permettre au client de modifier son trajet si le statut est "en_attente".
     */
    public function updateTrajet(Request $request)
    {
        $request->validate([
            'tracking'     => 'required|string',
            'lieu_depart'  => 'required|string|max:500',
            'lieu_arrivee' => 'required|string|max:500',
            'lat_depart'   => 'nullable|numeric',
            'lng_depart'   => 'nullable|numeric',
            'lat_arrivee'  => 'nullable|numeric',
            'lng_arrivee'  => 'nullable|numeric',
        ]);

        $reservation = ReservationTransport::where('tracking_number', $request->tracking)->firstOrFail();

        if ($reservation->statut !== 'en_attente') {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de modifier le trajet car cette réservation a déjà été acceptée.'
            ], 403);
        }

        $oldDepart = $reservation->lieu_depart;
        $oldArrivee = $reservation->lieu_arrivee;

        $reservation->update([
            'lieu_depart'  => $request->lieu_depart,
            'lieu_arrivee' => $request->lieu_arrivee,
            'lat_depart'   => $request->lat_depart ? (float)$request->lat_depart : null,
            'lng_depart'   => $request->lng_depart ? (float)$request->lng_depart : null,
            'lat_arrivee'  => $request->lat_arrivee ? (float)$request->lat_arrivee : null,
            'lng_arrivee'  => $request->lng_arrivee ? (float)$request->lng_arrivee : null,
        ]);

        // Message système dans le chat pour notification
        TransportConversation::create([
            'reservation_transport_id' => $reservation->id,
            'auteur'  => 'systeme',
            'message' => "🔄 Le client a mis à jour son trajet.\n• Départ : {$oldDepart} ➔ {$request->lieu_depart}\n• Arrivée : {$oldArrivee} ➔ {$request->lieu_arrivee}",
            'type'    => 'notification_systeme',
        ]);

        return response()->json([
            'success' => true,
            'lieu_depart' => $reservation->lieu_depart,
            'lieu_arrivee' => $reservation->lieu_arrivee,
        ]);
    }
}

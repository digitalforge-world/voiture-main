<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Revision; // Added this line

class RevisionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Revision::with('user');

        // Search by client name, car model, or plate
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('marque_vehicule', 'like', '%' . $request->search . '%')
                    ->orWhere('modele_vehicule', 'like', '%' . $request->search . '%')
                    ->orWhere('immatriculation', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by status
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Filter by today's revisions
        if ($request->filled('today')) {
            $query->whereDate('date_demande', today());
        }

        // Sort by newest first (today's revisions on top)
        $revisions = $query->latest('date_demande')->paginate(15)->withQueryString();

        return view('admin.revisions.index', compact('revisions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $revision = Revision::findOrFail($id);

        $validated = $request->validate([
            'statut' => 'required|in:en_attente,diagnostic_en_cours,devis_envoye,accepte,refuse,en_intervention,termine,annule',
            'diagnostic_technique' => 'nullable|string',
            'montant_devis' => 'nullable|numeric|min:0',
            'interventions_prevues' => 'nullable|string',
            'pieces_necessaires' => 'nullable|string',
            'notes_internes' => 'nullable|string',
            'montant_paye' => 'nullable|numeric|min:0',
            'statut_paiement' => 'nullable|in:non_paye,partiel,paye',
        ]);

        // Update the revision
        $revision->update([
            'statut' => $validated['statut'],
            'diagnostic_technique' => $validated['diagnostic_technique'],
            'diagnostic' => $validated['diagnostic_technique'], // Pour rétrocompatibilité
            'montant_devis' => $validated['montant_devis'] ?? $revision->montant_devis,
            'interventions_prevues' => $validated['interventions_prevues'] ?? $revision->interventions_prevues,
            'pieces_necessaires' => $validated['pieces_necessaires'] ?? $revision->pieces_necessaires,
            'notes_internes' => $validated['notes_internes'] ?? $revision->notes_internes,
            'notes' => $validated['notes_internes'] ?? $revision->notes, // Pour rétrocompatibilité
            'montant_paye' => $validated['montant_paye'] ?? 0,
            'statut_paiement' => $validated['statut_paiement'] ?? 'non_paye',
        ]);

        // Update diagnostic date if diagnostic is provided
        if (!empty($validated['diagnostic_technique']) && !$revision->date_diagnostic) {
            $revision->update(['date_diagnostic' => now()]);
        }

        // Update devis date if montant_devis is provided and status is devis_envoye
        if ($validated['montant_devis'] && $validated['statut'] === 'devis_envoye') {
            $revision->update(['date_devis' => now()]);
        }

        // TODO: Send notification to client if notify_client is checked
        if ($request->has('notify_client') && $request->notify_client) {
            // Implement email/SMS notification here
            // Example: Mail::to($revision->user->email)->send(new RevisionUpdated($revision));
        }

        return redirect()->route('admin.revisions.index')
            ->with('success', 'Révision mise à jour avec succès ! Le client sera notifié.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

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
            'prix_estime' => 'nullable|numeric|min:0'
        ]);

        $revision->update($validated);

        return redirect()->route('admin.revisions.index')
            ->with('success', 'Révision mise à jour avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

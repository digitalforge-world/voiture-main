<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $locations = \App\Models\Location::with(['user', 'voiture'])->latest('date_reservation')->paginate(15);
        $users = \App\Models\User::all();
        $voitures = \App\Models\VoitureLocation::where('disponible', true)->get();
        return view('admin.rentals.index', compact('locations', 'users', 'voitures'));
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
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'voiture_location_id' => 'required|exists:voitures_location,id',
            'date_debut' => 'required|date|after_or_equal:today',
            'date_fin' => 'required|date|after:date_debut',
            'montant_total' => 'required|numeric|min:0',
            'statut' => 'required|in:reserve,confirme,en_cours,termine,annule',
        ]);

        $location = new \App\Models\Location();
        $location->fill($validated);

        // Caution and daily price from vehicle
        $voiture = \App\Models\VoitureLocation::find($validated['voiture_location_id']);
        $location->caution = $voiture->caution ?? 0;
        $location->montant_location = $validated['montant_total'] - $location->caution;

        // Generating reference is handled by trigger but we can set it if we want
        $location->save();

        return redirect()->route('admin.rentals.index')->with('success', 'Nouvelle réservation enregistrée.');
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
        $location = \App\Models\Location::findOrFail($id);

        $validated = $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'statut' => 'required|in:reserve,confirme,en_cours,termine,annule',
            'montant_total' => 'required|numeric|min:0',
        ]);

        $location->update($validated);

        return redirect()->route('admin.rentals.index')->with('success', 'Contrat de location mis à jour.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $location = \App\Models\Location::findOrFail($id);
        $location->delete();
        return redirect()->route('admin.rentals.index')->with('success', 'Réservation supprimée.');
    }
}

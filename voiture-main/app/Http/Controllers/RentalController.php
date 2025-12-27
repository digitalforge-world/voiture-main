<?php

namespace App\Http\Controllers;

use App\Models\VoitureLocation;
use Illuminate\Http\Request;

class RentalController extends Controller
{
    public function index()
    {
        $voitures = VoitureLocation::where('disponible', true)
            ->latest()
            ->get();

        return view('rental.index', compact('voitures'));
    }

    public function book(Request $request, $id)
    {
        $car = VoitureLocation::findOrFail($id);
        
        $request->validate([
            'date_debut' => 'required|date|after:today',
            'date_fin' => 'required|date|after:date_debut',
        ]);

        $start = new \DateTime($request->date_debut);
        $end = new \DateTime($request->date_fin);
        $days = $start->diff($end)->days;
        
        $montantLocation = $car->prix_jour * $days;
        $montantTotal = $montantLocation + $car->caution;

        \App\Models\Location::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id() ?: 1,
            'voiture_location_id' => $car->id,
            'tracking_number' => 'TRK-' . strtoupper(\Illuminate\Support\Str::random(12)),
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'montant_location' => $montantLocation,
            'caution' => $car->caution,
            'montant_total' => $montantTotal,
            'statut' => 'reserve',
        ]);

        return redirect()->route('dashboard')->with('success', 'Votre réservation de véhicule a été enregistrée !');
    }
}

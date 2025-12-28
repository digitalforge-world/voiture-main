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
            ->paginate(9);

        return view('rental.index', compact('voitures'));
    }

    public function book(Request $request, $id)
    {
        $car = VoitureLocation::findOrFail($id);

        $request->validate([
            'date_debut' => 'required|date|after:today',
            'date_fin' => 'required|date|after:date_debut',
            'client_nom' => 'required|string|max:255',
            'client_telephone' => 'required|string|max:20',
            'client_email' => 'nullable|email|max:255',
        ]);

        $start = new \DateTime($request->date_debut);
        $end = new \DateTime($request->date_fin);
        $days = $start->diff($end)->days;

        $montantLocation = $car->prix_jour * $days;
        $montantTotal = $montantLocation + $car->caution;

        // Generate Tracking Number
        $trackingNumber = \App\Helpers\TrackingHelper::forRental();

        \App\Models\Location::create([
            'user_id' => null, // Anonymous
            'voiture_location_id' => $car->id,
            'tracking_number' => $trackingNumber,
            'client_nom' => $request->client_nom,
            'client_telephone' => $request->client_telephone,
            'client_email' => $request->client_email,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'montant_location' => $montantLocation,
            'caution' => $car->caution,
            'montant_total' => $montantTotal,
            'statut' => 'reserve',
            'reference' => 'LOC-' . strtoupper(\Illuminate\Support\Str::random(8)),
        ]);

        return redirect()->route('tracking.success')->with('tracking_number', $trackingNumber);
    }
}

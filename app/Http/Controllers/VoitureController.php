<?php

namespace App\Http\Controllers;

use App\Models\Voiture;
use App\Models\Port;
use Illuminate\Http\Request;

class VoitureController extends Controller
{
    public function index(Request $request)
    {
        $query = Voiture::query();

        if ($request->has('marque')) {
            $query->where('marque', 'like', '%' . $request->marque . '%');
        }

        if ($request->has('prix_max')) {
            $query->where('prix', '<=', $request->prix_max);
        }

        $voitures = $query->whereIn('disponibilite', ['disponible', 'en_transit'])
            ->latest()
            ->paginate(9);

        $marques = Voiture::distinct()->pluck('marque');

        return view('cars.index', compact('voitures', 'marques'));
    }

    public function show($id)
    {
        $voiture = Voiture::findOrFail($id);
        $ports = Port::where('actif', true)->get();

        return view('cars.show', compact('voiture', 'ports'));
    }

    public function order(Request $request, $id)
    {
        $request->validate([
            'port_id' => 'required|exists:ports,id',
            'client_nom' => 'required|string|max:255',
            'client_telephone' => 'required|string|max:20',
            'client_email' => 'nullable|email|max:255',
        ]);

        $voiture = Voiture::findOrFail($id);
        $port = Port::findOrFail($request->port_id);

        // Calculate fees
        $fraisPort = $port->frais_base;
        $fraisDouane = $voiture->prix * 0.10;
        $montantTotal = $voiture->prix + $fraisPort + $fraisDouane;

        // Generate Secure Tracking Number
        $trackingNumber = \App\Helpers\TrackingHelper::forCar();

        $commande = \App\Models\CommandeVoiture::create([
            'user_id' => null, // No user account needed anymore
            'voiture_id' => $voiture->id,
            'port_destination_id' => $port->id,
            'prix_voiture' => $voiture->prix,
            'frais_port' => $fraisPort,
            'frais_douane' => $fraisDouane,
            'montant_total' => $montantTotal,
            'reste_a_payer' => $montantTotal,
            'statut' => 'en_attente',
            'reference' => 'CV-' . strtoupper(\Illuminate\Support\Str::random(8)),
            'tracking_number' => $trackingNumber,
            'client_nom' => $request->client_nom,
            'client_telephone' => $request->client_telephone,
            'client_email' => $request->client_email,
        ]);

        // Update car availability
        $voiture->update(['disponibilite' => 'reserve']);

        // Redirect to success page with tracking number
        return redirect()->route('tracking.success')->with('tracking_number', $trackingNumber);
    }
}

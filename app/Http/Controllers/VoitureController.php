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

        if ($request->filled('marque')) {
            $query->where('marque', 'like', '%' . $request->marque . '%');
        }

        if ($request->filled('prix_max')) {
            $query->where('prix', '<=', $request->prix_max);
        }

        // Les voitures à vendre sont toujours visibles (catalogue illimité).
        $voitures = $query->latest()
            ->paginate(9);

        // Marques depuis la configuration admin
        $marquesSetting = \App\Models\ParametreSysteme::where('cle', 'marques_disponibles')->value('valeur');
        $marques = $marquesSetting
            ? collect(array_map('trim', explode(',', $marquesSetting)))->sort()->values()
            : Voiture::distinct()->pluck('marque')->sort()->values();

        return view('cars.index', compact('voitures', 'marques'));
    }

    public function show(Voiture $voiture)
    {
        $ports = Port::where('actif', true)->get();

        return view('cars.show', compact('voiture', 'ports'));
    }

    public function order(Request $request, Voiture $voiture)
    {
        $request->validate([
            'port_id' => 'required|exists:ports,id',
            'client_nom' => 'required|string|max:255',
            'client_telephone' => 'required|string|max:20',
            'client_email' => 'nullable|email|max:255',
        ]);

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

        // Note: Les voitures à vendre restent toujours visibles au catalogue (stock illimité / sur commande).
        // Seules les voitures de location changent de disponibilité.

        // Redirect to success page with tracking number
        return redirect()->route('tracking.success')->with('tracking_number', $trackingNumber);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Revision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RevisionController extends Controller
{
    public function index()
    {
        $revisions = Revision::where('user_id', Auth::id())
            ->orWhere('client_email', Auth::user()->email ?? '')
            ->latest('date_demande')
            ->paginate(10);

        return view('revisions.index', compact('revisions'));
    }

    public function create()
    {
        return view('revisions.create');
    }

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

        // Generate Tracking Number
        $trackingNumber = \App\Helpers\TrackingHelper::forRevision();

        Revision::create([
            'user_id' => null, // Anonymous
            'marque_vehicule' => $request->marque_vehicule,
            'modele_vehicule' => $request->modele_vehicule,
            'annee_vehicule' => $request->annee_vehicule,
            'immatriculation' => $request->immatriculation,
            'kilometrage' => $request->kilometrage,
            'probleme_description' => $request->probleme_description,
            'type_revision' => $request->type_revision ?? 'complete', // Corrected field name
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

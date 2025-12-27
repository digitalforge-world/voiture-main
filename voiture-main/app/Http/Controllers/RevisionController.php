<?php

namespace App\Http\Controllers;

use App\Models\Revision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RevisionController extends Controller
{
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
        ]);

        Revision::create([
            'user_id' => Auth::id() ?: 1,
            'marque_vehicule' => $request->marque_vehicule,
            'modele_vehicule' => $request->modele_vehicule,
            'annee_vehicule' => $request->annee_vehicule,
            'immatriculation' => $request->immatriculation,
            'kilometrage' => $request->kilometrage,
            'probleme_description' => $request->probleme_description,
            'type_revision' => $request->type_intervention ?? 'complete',
            'statut' => 'en_attente',
            'reference' => 'REV-' . strtoupper(Str::random(8)),
            'tracking_number' => 'TRK-' . strtoupper(Str::random(12)),
        ]);

        return redirect()->route('dashboard')->with('success', 'Votre demande de révision a été envoyée !');
    }
}

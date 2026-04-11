<?php

namespace App\Http\Controllers;

use App\Models\Voiture;
use App\Models\VoitureLocation;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredCars = Voiture::where('disponibilite', 'disponible')
            ->latest()
            ->take(4)
            ->get();

        $featuredRentals = VoitureLocation::where('disponible', true)
            ->latest()
            ->take(4)
            ->get();

        // Marques depuis la configuration admin
        $marquesSetting = \App\Models\ParametreSysteme::where('cle', 'marques_disponibles')->value('valeur');
        $marques = $marquesSetting
            ? collect(array_map('trim', explode(',', $marquesSetting)))->sort()->values()
            : Voiture::distinct()->pluck('marque')->filter()->sort()->values();

        // Pays depuis la configuration admin ou depuis les voitures
        $paysSetting = \App\Models\ParametreSysteme::where('cle', 'pays_disponibles')->value('valeur');
        $pays = $paysSetting
            ? collect(array_map('trim', explode(',', $paysSetting)))->sort()->values()
            : Voiture::distinct()->pluck('pays_origine')->filter()->sort()->values();

        return view('welcome', compact('featuredCars', 'featuredRentals', 'marques', 'pays'));
    }
}

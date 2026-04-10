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
            ->take(3)
            ->get();

        return view('welcome', compact('featuredCars', 'featuredRentals'));
    }
}

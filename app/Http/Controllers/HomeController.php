<?php

namespace App\Http\Controllers;

use App\Models\Voiture;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredCars = Voiture::where('disponibilite', 'disponible')
            ->latest()
            ->take(3)
            ->get();

        return view('welcome', compact('featuredCars'));
    }
}

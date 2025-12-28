<?php

namespace App\Http\Controllers;

use App\Models\Voiture;
use App\Models\CommandeVoiture;
use App\Models\User;
use App\Models\Revision;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $totalCars = Voiture::count();
        $pendingOrders = CommandeVoiture::where('statut', 'en_attente')->count();
        $totalUsers = User::count();
        $pendingRevisions = Revision::where('statut', 'en_attente')->count();

        $recentOrders = CommandeVoiture::with('user', 'voiture')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('totalCars', 'pendingOrders', 'totalUsers', 'pendingRevisions', 'recentOrders'));
    }
}

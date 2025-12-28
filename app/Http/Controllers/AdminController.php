<?php

namespace App\Http\Controllers;

use App\Models\Voiture;
use App\Models\CommandeVoiture;
use App\Models\User;
use App\Models\Revision;
use App\Models\Location;
use App\Models\PieceDetachee;
use App\Models\Paiement;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Indicateurs clés
        $totalCars = Voiture::count();
        $pendingOrders = CommandeVoiture::where('statut', 'en_attente')->count();
        $totalUsers = User::count();
        $totalClients = User::where('role', 'client')->count();
        $pendingRevisions = Revision::where('statut', 'en_attente')->count();
        $activeRentals = Location::where('statut', 'en_cours')->count();
        $availableParts = PieceDetachee::where('stock', '>', 0)->count();

        // Revenus simples (somme des paiements validés)
        $totalRevenue = \App\Models\Paiement::where('statut', 'valide')->sum('montant');

        $recentOrders = CommandeVoiture::with('user', 'voiture')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalCars',
            'pendingOrders',
            'totalUsers',
            'totalClients',
            'pendingRevisions',
            'activeRentals',
            'availableParts',
            'totalRevenue',
            'recentOrders'
        ));
    }
}

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
        $stats = [
            'total_cars' => Voiture::count(),
            'pending_orders' => CommandeVoiture::where('statut', 'en_attente')->count(),
            'total_users' => User::count(),
            'pending_revisions' => Revision::where('statut', 'en_attente')->count(),
        ];

        $recentOrders = CommandeVoiture::with('user', 'voiture')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }
}

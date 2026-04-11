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
    public function globalSearch(Request $request)
    {
        $query = $request->input('q');

        if (!$query) {
            return redirect()->back();
        }

        $results = [
            'cars' => Voiture::where('marque', 'LIKE', "%{$query}%")
                ->orWhere('modele', 'LIKE', "%{$query}%")
                ->orWhere('numero_chassis', 'LIKE', "%{$query}%")
                ->take(5)->get(),
            'orders' => CommandeVoiture::where('reference', 'LIKE', "%{$query}%")
                ->orWhere('tracking_number', 'LIKE', "%{$query}%")
                ->orWhere('client_nom', 'LIKE', "%{$query}%")
                ->orWhereHas('user', function ($q) use ($query) {
                    $q->where('nom', 'LIKE', "%{$query}%")->orWhere('prenom', 'LIKE', "%{$query}%");
                })
                ->take(5)->get(),
            'parts' => PieceDetachee::where('nom', 'LIKE', "%{$query}%")
                ->orWhere('reference', 'LIKE', "%{$query}%")
                ->take(5)->get(),
            'users' => User::where('nom', 'LIKE', "%{$query}%")
                ->orWhere('prenom', 'LIKE', "%{$query}%")
                ->orWhere('email', 'LIKE', "%{$query}%")
                ->take(5)->get(),
            'rentals' => Location::with('voiture')->where('reference', 'LIKE', "%{$query}%")
                ->orWhere('client_nom', 'LIKE', "%{$query}%")
                ->orWhereHas('user', function ($q) use ($query) {
                    $q->where('nom', 'LIKE', "%{$query}%")->orWhere('prenom', 'LIKE', "%{$query}%");
                })
                ->orWhereHas('voiture', function($q) use ($query){
                    $q->where('immatriculation', 'LIKE', "%{$query}%");
                })
                ->take(5)->get(),
            'part_orders' => \App\Models\CommandePiece::with('lignes.piece')->where('reference', 'LIKE', "%{$query}%")
                ->orWhere('tracking_number', 'LIKE', "%{$query}%")
                ->orWhere('client_nom', 'LIKE', "%{$query}%")
                ->orWhereHas('user', function ($q) use ($query) {
                    $q->where('nom', 'LIKE', "%{$query}%")->orWhere('prenom', 'LIKE', "%{$query}%");
                })
                ->orWhereHas('lignes.piece', function($q) use ($query){
                    $q->where('nom', 'LIKE', "%{$query}%")->orWhere('reference', 'LIKE', "%{$query}%");
                })
                ->take(5)->get(),
            'revisions' => Revision::where('reference', 'LIKE', "%{$query}%")
                ->orWhere('probleme_description', 'LIKE', "%{$query}%")
                ->orWhere('immatriculation', 'LIKE', "%{$query}%")
                ->orWhere('marque_vehicule', 'LIKE', "%{$query}%")
                ->take(5)->get(),
        ];

        return view('admin.search.results', compact('results', 'query'));
    }
}

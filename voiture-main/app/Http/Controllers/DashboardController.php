<?php

namespace App\Http\Controllers;

use App\Models\CommandeVoiture;
use App\Models\Location;
use App\Models\CommandePiece;
use App\Models\Revision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // For demonstration, using a fixed user if not logged in
        // In real app, use Auth::user()
        $userId = Auth::id() ?: 1;

        $commandesVoitures = CommandeVoiture::with('voiture', 'port')
            ->where('user_id', $userId)
            ->latest()
            ->get();

        $locations = Location::with('voitureLocation')
            ->where('user_id', $userId)
            ->latest()
            ->get();

        $commandesPieces = CommandePiece::where('user_id', $userId)
            ->latest()
            ->get();

        $revisions = Revision::where('user_id', $userId)
            ->latest()
            ->get();

        return view('dashboard', compact('commandesVoitures', 'locations', 'commandesPieces', 'revisions'));
    }
}

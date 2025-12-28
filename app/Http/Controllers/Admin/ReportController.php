<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        // Monthly Revenue Portfolio (Last 6 Months)
        $revenueData = \App\Models\Paiement::where('statut', 'valide')
            ->where('date_paiement', '>=', now()->subMonths(6))
            ->selectRaw('DATE_FORMAT(date_paiement, "%M") as month, SUM(montant) as total')
            ->groupBy('month')
            ->orderBy('date_paiement')
            ->get();

        // Order Velocity Metrics
        $carOrdersStats = \App\Models\CommandeVoiture::selectRaw('statut, count(*) as count')
            ->groupBy('statut')
            ->get();

        $partOrdersStats = \App\Models\CommandePiece::selectRaw('statut, count(*) as count')
            ->groupBy('statut')
            ->get();

        // Fleet Utilization
        $rentalStats = \App\Models\Location::selectRaw('statut, count(*) as count')
            ->groupBy('statut')
            ->get();

        // Inventory Value
        $totalInventoryValue = \App\Models\PieceDetachee::sum(\Illuminate\Support\Facades\DB::raw('prix * stock'));

        return view('admin.reports.index', compact(
            'revenueData',
            'carOrdersStats',
            'partOrdersStats',
            'rentalStats',
            'totalInventoryValue'
        ));
    }
}

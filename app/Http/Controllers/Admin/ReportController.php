<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Paiement;
use App\Models\CommandeVoiture;
use App\Models\CommandePiece;
use App\Models\Location;
use App\Models\PieceDetachee;

class ReportController extends Controller
{
    public function index()
    {
        // Monthly Revenue Portfolio (Last 6 Months) - Database agnostic supporting SQLite and MySQL
        $driver = DB::connection()->getDriverName();
        if ($driver === 'sqlite') {
            $revenueData = Paiement::where('statut', 'valide')
                ->where('date_paiement', '>=', now()->subMonths(6))
                ->selectRaw('strftime("%m", date_paiement) as month_num, SUM(montant) as total')
                ->groupBy('month_num')
                ->orderBy('date_paiement')
                ->get()
                ->map(function ($item) {
                    // Create date with current year, extracted month number, and 1st day of month
                    $monthName = Carbon::create(null, (int)$item->month_num, 1)->translatedFormat('F');
                    return (object) [
                        'month' => $monthName,
                        'total' => $item->total
                    ];
                });
        } else {
            $revenueData = Paiement::where('statut', 'valide')
                ->where('date_paiement', '>=', now()->subMonths(6))
                ->selectRaw('DATE_FORMAT(date_paiement, "%M") as month, SUM(montant) as total')
                ->groupBy('month')
                ->orderBy('date_paiement')
                ->get();
        }

        // Order Velocity Metrics
        $carOrdersStats = CommandeVoiture::selectRaw('statut, count(*) as count')
            ->groupBy('statut')
            ->get();

        $partOrdersStats = CommandePiece::selectRaw('statut, count(*) as count')
            ->groupBy('statut')
            ->get();

        // Fleet Utilization
        $rentalStats = Location::selectRaw('statut, count(*) as count')
            ->groupBy('statut')
            ->get();

        // Inventory Value - Rewritten cleanly to prevent static analysis warnings and keep it robust
        $totalInventoryValue = PieceDetachee::selectRaw('SUM(prix * stock) as total_val')
            ->value('total_val') ?: 0;

        return view('admin.reports.index', compact(
            'revenueData',
            'carOrdersStats',
            'partOrdersStats',
            'rentalStats',
            'totalInventoryValue'
        ));
    }
}

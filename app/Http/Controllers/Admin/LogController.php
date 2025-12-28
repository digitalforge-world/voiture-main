<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\LogActivite::with('user')->orderBy('date_action', 'desc');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('action', 'LIKE', "%{$search}%")
                    ->orWhere('table_concernee', 'LIKE', "%{$search}%")
                    ->orWhere('details', 'LIKE', "%{$search}%")
                    ->orWhere('enregistrement_id', 'LIKE', "%{$search}%")
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('nom', 'LIKE', "%{$search}%")
                            ->orWhere('prenom', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%");
                    });
            });
        }

        if ($dateStart = $request->input('date_start')) {
            $query->whereDate('date_action', '>=', $dateStart);
        }

        if ($dateEnd = $request->input('date_end')) {
            $query->whereDate('date_action', '<=', $dateEnd);
        }

        $logs = $query->paginate(50)->withQueryString();

        return view('admin.logs.index', compact('logs'));
    }
}

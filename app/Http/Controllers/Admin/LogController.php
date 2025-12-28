<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index()
    {
        $logs = \App\Models\LogActivite::with('user')
            ->orderBy('date_log', 'desc')
            ->paginate(20);

        return view('admin.logs.index', compact('logs'));
    }
}

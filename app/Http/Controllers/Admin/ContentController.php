<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function index()
    {
        $parameters = \App\Models\ParametreSysteme::orderBy('cle')->get();
        return view('admin.content.index', compact('parameters'));
    }
}

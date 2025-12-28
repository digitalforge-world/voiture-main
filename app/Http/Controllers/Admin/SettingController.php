<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = \App\Models\ParametreSysteme::all()->groupBy('groupe');
        return view('admin.settings.index', compact('settings'));
    }

    public function updateBulk(Request $request)
    {
        $settings = $request->input('settings', []);

        // Handle regular settings
        foreach ($settings as $cle => $valeur) {
            \App\Models\ParametreSysteme::where('cle', $cle)->update(['valeur' => $valeur]);
        }

        // Handle file uploads
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $cle => $file) {
                if ($file->isValid()) {
                    $path = $file->store('branding', 'public');
                    \App\Models\ParametreSysteme::where('cle', $cle)->update(['valeur' => '/storage/' . $path]);
                }
            }
        }

        return redirect()->back()->with('success', 'Tous les paramètres ont été mis à jour avec succès.');
    }
}

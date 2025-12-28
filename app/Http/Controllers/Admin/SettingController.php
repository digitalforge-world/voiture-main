<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        return view('admin.settings.index');
    }

    public function update(Request $request, string $id)
    {
        $parameter = \App\Models\ParametreSysteme::findOrFail($id);

        $validated = $request->validate([
            'valeur' => 'required',
        ]);

        $parameter->update($validated);

        return redirect()->back()->with('success', 'Paramètre système mis à jour.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Voiture;
class VoitureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Voiture::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('marque', 'LIKE', "%{$search}%")
                    ->orWhere('modele', 'LIKE', "%{$search}%")
                    ->orWhere('vin', 'LIKE', "%{$search}%");
            });
        }

        if ($availability = $request->input('availability')) {
            $query->where('disponibilite', $availability);
        }

        if ($condition = $request->input('condition')) {
            $query->where('etat', $condition);
        }

        $voitures = $query->latest('date_creation')->paginate(15)->withQueryString();
        return view('admin.cars.index', compact('voitures'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'marque' => 'required|string|max:50',
            'modele' => 'required|string|max:50',
            'annee' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'prix' => 'required|numeric|min:0',
            'kilometrage' => 'required|integer|min:0',
            'etat' => 'required|in:neuf,occasion,reconditionne',
            'vin' => 'nullable|string|max:50|unique:voitures,vin',
            'disponibilite' => 'required|in:disponible,importation,reserve',
            'photo_principale' => 'nullable|image|max:2048',
        ]);

        $voiture = new \App\Models\Voiture($validated);
        $voiture->date_creation = now();

        if ($request->hasFile('photo_principale')) {
            $path = $request->file('photo_principale')->store('voitures', 'public');
            $voiture->photo_principale = '/storage/' . $path;
        }

        $voiture->save();

        return redirect()->route('admin.cars.index')->with('success', 'Véhicule ajouté au catalogue.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $voiture = Voiture::findOrFail($id);

        $validated = $request->validate([
            'prix' => 'required|numeric|min:0',
            'disponibilite' => 'required|in:disponible,importation,reserve,vendu',
            'kilometrage' => 'required|integer|min:0',
            'etat' => 'required|in:neuf,occasion,reconditionne',
        ]);

        $voiture->update($validated);

        return redirect()->route('admin.cars.index')->with('success', 'Catalogue mis à jour.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $voiture = Voiture::findOrFail($id);
        $voiture->delete();
        return redirect()->route('admin.cars.index')->with('success', 'Véhicule retiré du catalogue.');
    }
}

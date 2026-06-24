<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Driver::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                    ->orWhere('prenom', 'LIKE', "%{$search}%")
                    ->orWhere('telephone', 'LIKE', "%{$search}%")
                    ->orWhere('vehicule_marque', 'LIKE', "%{$search}%")
                    ->orWhere('vehicule_modele', 'LIKE', "%{$search}%")
                    ->orWhere('vehicule_immatriculation', 'LIKE', "%{$search}%");
            });
        }

        if ($statut = $request->input('statut')) {
            $query->where('statut', $statut);
        }

        $drivers = $query->latest()->paginate(15)->withQueryString();

        return view('admin.drivers.index', compact('drivers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom'                      => 'required|string|max:100',
            'prenom'                   => 'required|string|max:100',
            'telephone'                => 'required|string|max:30',
            'photo'                    => 'nullable|image|max:2048',
            'vehicule_marque'          => 'required|string|max:100',
            'vehicule_modele'          => 'required|string|max:100',
            'vehicule_immatriculation' => 'required|string|max:50',
            'vehicule_couleur'         => 'nullable|string|max:50',
            'statut'                   => 'required|in:actif,inactif',
            'identifiant'              => 'required|string|unique:drivers,identifiant|max:50',
            'mot_de_passe'             => 'required|string|min:4|max:100',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('drivers', 'public');
            $validated['photo'] = '/storage/' . $path;
        }

        Driver::create($validated);

        return redirect()->route('admin.drivers.index')->with('success', 'Chauffeur ajouté avec succès.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $driver = Driver::findOrFail($id);

        $validated = $request->validate([
            'nom'                      => 'required|string|max:100',
            'prenom'                   => 'required|string|max:100',
            'telephone'                => 'required|string|max:30',
            'photo'                    => 'nullable|image|max:2048',
            'vehicule_marque'          => 'required|string|max:100',
            'vehicule_modele'          => 'required|string|max:100',
            'vehicule_immatriculation' => 'required|string|max:50',
            'vehicule_couleur'         => 'nullable|string|max:50',
            'statut'                   => 'required|in:actif,inactif',
            'identifiant'              => 'required|string|max:50|unique:drivers,identifiant,' . $id,
            'mot_de_passe'             => 'nullable|string|min:4|max:100',
        ]);

        if (empty($validated['mot_de_passe'])) {
            unset($validated['mot_de_passe']);
        }

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($driver->photo && str_starts_with($driver->photo, '/storage/')) {
                $oldPath = str_replace('/storage/', '', $driver->photo);
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('photo')->store('drivers', 'public');
            $validated['photo'] = '/storage/' . $path;
        }

        $driver->update($validated);

        return redirect()->route('admin.drivers.index')->with('success', 'Chauffeur mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $driver = Driver::findOrFail($id);

        // Delete photo from storage if exists
        if ($driver->photo && str_starts_with($driver->photo, '/storage/')) {
            $oldPath = str_replace('/storage/', '', $driver->photo);
            Storage::disk('public')->delete($oldPath);
        }

        $driver->delete();

        return redirect()->route('admin.drivers.index')->with('success', 'Chauffeur supprimé avec succès.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Voiture;
use App\Models\PhotoVoiture;
use Illuminate\Support\Facades\Storage;

class VoitureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Voiture::query()->with('photos');

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
            'photos.*' => 'nullable|image|max:2048',
        ]);

        $voiture = new Voiture($validated);
        $voiture->date_creation = now();
        $voiture->save();

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $file) {
                $path = $file->store('voitures', 'public');
                $url = '/storage/' . $path;

                $isPrincipal = ($index === 0);

                PhotoVoiture::create([
                    'voiture_id' => $voiture->id,
                    'url' => $url,
                    'ordre' => $index + 1,
                    'principale' => $isPrincipal
                ]);

                if ($isPrincipal) {
                    $voiture->update(['photo_principale' => $url]);
                }
            }
        }

        return redirect()->route('admin.cars.index')->with('success', 'Véhicule ajouté avec sa galerie photos.');
    }

    public function update(Request $request, string $id)
    {
        $voiture = Voiture::findOrFail($id);

        $validated = $request->validate([
            'prix' => 'required|numeric|min:0',
            'disponibilite' => 'required|in:disponible,importation,reserve,vendu',
            'kilometrage' => 'required|integer|min:0',
            'etat' => 'required|in:neuf,occasion,reconditionne',
            'photos.*' => 'nullable|image|max:2048',
        ]);

        $voiture->update($validated);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $file) {
                $path = $file->store('voitures', 'public');
                $url = '/storage/' . $path;

                PhotoVoiture::create([
                    'voiture_id' => $voiture->id,
                    'url' => $url,
                    'ordre' => $voiture->photos()->count() + 1,
                    'principale' => false
                ]);
            }
        }

        return redirect()->route('admin.cars.index')->with('success', 'Catalogue et galerie mis à jour.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $voiture = Voiture::findOrFail($id);

        // Delete all photos from storage
        foreach ($voiture->photos as $photo) {
            $path = str_replace('/storage/', '', $photo->url);
            Storage::disk('public')->delete($path);
        }

        $voiture->delete();
        return redirect()->route('admin.cars.index')->with('success', 'Véhicule et sa galerie retirés du catalogue.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VoitureLocation;
use App\Models\PhotoVoitureLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FleetController extends Controller
{
    public function index(Request $request)
    {
        $query = VoitureLocation::with('photos');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('marque', 'LIKE', "%{$search}%")
                    ->orWhere('modele', 'LIKE', "%{$search}%")
                    ->orWhere('immatriculation', 'LIKE', "%{$search}%");
            });
        }

        $fleet = $query->latest()->paginate(15)->withQueryString();
        return view('admin.rentals.index', compact('fleet'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'marque' => 'required|string|max:50',
            'modele' => 'required|string|max:50',
            'annee' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'immatriculation' => 'nullable|string|max:20',
            'couleur' => 'nullable|string|max:30',
            'kilometrage' => 'nullable|integer|min:0',
            'transmission' => 'nullable|in:manuelle,automatique',
            'carburant' => 'nullable|in:essence,diesel,hybride,electrique',
            'nombre_places' => 'nullable|integer|min:1|max:50',
            'prix_jour' => 'required|numeric|min:0',
            'caution' => 'nullable|numeric|min:0',
            'categorie' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'etat_general' => 'nullable|string|max:50',
            'photo_principale' => 'nullable|image|max:2048',
            'photos.*' => 'nullable|image|max:2048',
        ]);

        $voiture = VoitureLocation::create($validated);

        // Photo principale
        if ($request->hasFile('photo_principale')) {
            $path = $request->file('photo_principale')->store('fleet', 'public');
            $url = '/storage/' . $path;
            $voiture->update(['photo_principale' => $url]);

            PhotoVoitureLocation::create([
                'voiture_location_id' => $voiture->id,
                'url' => $url,
                'ordre' => 0,
                'principale' => true,
            ]);
        }

        // Photos additionnelles
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $file) {
                $path = $file->store('fleet', 'public');
                $url = '/storage/' . $path;

                PhotoVoitureLocation::create([
                    'voiture_location_id' => $voiture->id,
                    'url' => $url,
                    'ordre' => $index + 1,
                    'principale' => !$voiture->photo_principale && $index === 0,
                ]);

                if (!$voiture->photo_principale && $index === 0) {
                    $voiture->update(['photo_principale' => $url]);
                }
            }
        }

        return redirect()->back()->with('success', 'Véhicule ajouté à la flotte avec sa galerie.');
    }

    public function update(Request $request, string $id)
    {
        $voiture = VoitureLocation::findOrFail($id);

        $validated = $request->validate([
            'marque' => 'required|string|max:50',
            'modele' => 'required|string|max:50',
            'annee' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'immatriculation' => 'nullable|string|max:20',
            'prix_jour' => 'required|numeric|min:0',
            'caution' => 'nullable|numeric|min:0',
            'disponible' => 'nullable|boolean',
            'description' => 'nullable|string',
            'etat_general' => 'nullable|string|max:50',
            'photo_principale' => 'nullable|image|max:2048',
            'photos.*' => 'nullable|image|max:2048',
        ]);

        $voiture->update($validated);

        // Nouvelle photo principale
        if ($request->hasFile('photo_principale')) {
            $path = $request->file('photo_principale')->store('fleet', 'public');
            $url = '/storage/' . $path;
            $voiture->update(['photo_principale' => $url]);

            PhotoVoitureLocation::where('voiture_location_id', $voiture->id)->update(['principale' => false]);

            PhotoVoitureLocation::create([
                'voiture_location_id' => $voiture->id,
                'url' => $url,
                'ordre' => 0,
                'principale' => true,
            ]);
        }

        // Photos additionnelles
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $file) {
                $path = $file->store('fleet', 'public');
                $url = '/storage/' . $path;

                PhotoVoitureLocation::create([
                    'voiture_location_id' => $voiture->id,
                    'url' => $url,
                    'ordre' => $voiture->photos()->count() + 1,
                    'principale' => false,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Véhicule de flotte mis à jour.');
    }

    public function destroy(string $id)
    {
        $voiture = VoitureLocation::findOrFail($id);

        foreach ($voiture->photos as $photo) {
            $path = str_replace('/storage/', '', $photo->url);
            Storage::disk('public')->delete($path);
        }

        $voiture->delete();
        return redirect()->back()->with('success', 'Véhicule retiré de la flotte.');
    }

    public function deletePhoto(PhotoVoitureLocation $photo)
    {
        try {
            $path = str_replace('/storage/', '', $photo->url);
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            $voiture = $photo->voitureLocation;
            if ($photo->principale && $voiture) {
                $voiture->update(['photo_principale' => null]);
            }

            $photo->delete();
            return response()->json(['success' => true, 'message' => 'Photo supprimée']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}

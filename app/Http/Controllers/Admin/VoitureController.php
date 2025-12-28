<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Voiture;
use App\Models\PhotoVoiture;
use App\Models\VideoVoiture;
use Illuminate\Support\Facades\Storage;

class VoitureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Voiture::query()->with(['photos', 'videos']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('marque', 'LIKE', "%{$search}%")
                    ->orWhere('modele', 'LIKE', "%{$search}%")
                    ->orWhere('numero_chassis', 'LIKE', "%{$search}%");
            });
        }

        if ($availability = $request->input('availability')) {
            $query->where('disponibilite', $availability);
        }

        if ($condition = $request->input('condition')) {
            $query->where('etat', $condition);
        }

        $voitures = $query->latest()->paginate(15)->withQueryString();
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
            'etat' => 'required|in:neuf,occasion,excellent,bon,reconditionne',
            'pays_origine' => 'required|string|max:50',
            'numero_chassis' => 'nullable|string|max:50|unique:voitures,numero_chassis',
            'disponibilite' => 'required|in:disponible,importation,reserve',
            'description' => 'nullable|string',

            // Technical Specs
            'puissance' => 'nullable|string|max:20',
            'moteur' => 'nullable|string|max:50',
            'transmission' => 'nullable|in:manuelle,automatique,semi-automatique',
            'carburant' => 'nullable|in:essence,diesel,hybride,electrique,gpl',
            'consommation_mixte' => 'nullable|string|max:20',
            'vitesse_max' => 'nullable|string|max:20',
            'acceleration_0_100' => 'nullable|string|max:20',
            'type_vehicule' => 'nullable|string|max:30',

            // Market & History
            'origine_marche' => 'nullable|string|max:50',
            'nombre_proprietaires' => 'nullable|integer|min:1',
            'carnet_entretien_ajour' => 'nullable|boolean',
            'non_fumeur' => 'nullable|boolean',

            // JSON Equipment
            'equipements_details' => 'nullable|array',

            'photo_principale' => 'nullable|image|max:2048',
            'photos.*' => 'nullable|image|max:2048',
            'videos.*' => 'nullable|mimetypes:video/mp4,video/quicktime|max:20480',
        ]);

        $voiture = new Voiture($validated);
        $voiture->save();

        if ($request->hasFile('photo_principale')) {
            $path = $request->file('photo_principale')->store('voitures', 'public');
            $url = '/storage/' . $path;

            $voiture->update(['photo_principale' => $url]);

            PhotoVoiture::create([
                'voiture_id' => $voiture->id,
                'url' => $url,
                'ordre' => 0,
                'principale' => true
            ]);
        }

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $file) {
                $path = $file->store('voitures', 'public');
                $url = '/storage/' . $path;

                PhotoVoiture::create([
                    'voiture_id' => $voiture->id,
                    'url' => $url,
                    'ordre' => $index + 1,
                    'principale' => !$voiture->photo_principale && $index === 0
                ]);

                if (!$voiture->photo_principale && $index === 0) {
                    $voiture->update(['photo_principale' => $url]);
                }
            }
        }

        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $index => $file) {
                $path = $file->store('voitures/videos', 'public');
                $url = '/storage/' . $path;

                VideoVoiture::create([
                    'voiture_id' => $voiture->id,
                    'url' => $url,
                    'ordre' => $index + 1
                ]);
            }
        }

        return redirect()->route('admin.cars.index')->with('success', 'Véhicule ajouté avec sa galerie multimédia.');
    }

    public function update(Request $request, string $id)
    {
        $voiture = Voiture::findOrFail($id);

        $validated = $request->validate([
            'prix' => 'required|numeric|min:0',
            'disponibilite' => 'required|in:disponible,importation,reserve,vendu',
            'kilometrage' => 'required|integer|min:0',
            'etat' => 'required|in:neuf,occasion,excellent,bon,reconditionne',
            'pays_origine' => 'required|string|max:50',
            'description' => 'nullable|string',

            // Technical Specs
            'puissance' => 'nullable|string|max:20',
            'moteur' => 'nullable|string|max:50',
            'transmission' => 'nullable|in:manuelle,automatique,semi-automatique',
            'carburant' => 'nullable|in:essence,diesel,hybride,electrique,gpl',
            'consommation_mixte' => 'nullable|string|max:20',
            'vitesse_max' => 'nullable|string|max:20',
            'acceleration_0_100' => 'nullable|string|max:20',
            'type_vehicule' => 'nullable|string|max:30',

            // Market & History
            'origine_marche' => 'nullable|string|max:50',
            'nombre_proprietaires' => 'nullable|integer|min:1',
            'carnet_entretien_ajour' => 'nullable|boolean',
            'non_fumeur' => 'nullable|boolean',

            // JSON Equipment
            'equipements_details' => 'nullable|array',

            'photo_principale' => 'nullable|image|max:2048',
            'photos.*' => 'nullable|image|max:2048',
            'videos.*' => 'nullable|mimetypes:video/mp4,video/quicktime|max:20480',
        ]);

        $voiture->update($validated);

        if ($request->hasFile('photo_principale')) {
            $path = $request->file('photo_principale')->store('voitures', 'public');
            $url = '/storage/' . $path;

            $voiture->update(['photo_principale' => $url]);

            // Mark others as not principal
            PhotoVoiture::where('voiture_id', $voiture->id)->update(['principale' => false]);

            PhotoVoiture::create([
                'voiture_id' => $voiture->id,
                'url' => $url,
                'ordre' => 0,
                'principale' => true
            ]);
        }

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

        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $index => $file) {
                $path = $file->store('voitures/videos', 'public');
                $url = '/storage/' . $path;

                VideoVoiture::create([
                    'voiture_id' => $voiture->id,
                    'url' => $url,
                    'ordre' => $voiture->videos()->count() + 1
                ]);
            }
        }

        return redirect()->route('admin.cars.index')->with('success', 'Catalogue et galerie multimédia mis à jour.');
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

        foreach ($voiture->videos as $video) {
            $path = str_replace('/storage/', '', $video->url);
            Storage::disk('public')->delete($path);
        }

        $voiture->delete();
        return redirect()->route('admin.cars.index')->with('success', 'Véhicule et sa galerie retirés du catalogue.');
    }

    public function deletePhoto(PhotoVoiture $photo)
    {
        try {
            $path = str_replace('/storage/', '', $photo->url);
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            $voiture = $photo->voiture;
            if ($photo->principale && $voiture) {
                $voiture->update(['photo_principale' => null]);
            }

            $photo->delete();
            return response()->json(['success' => true, 'message' => 'Archive supprimée']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteVideo(VideoVoiture $video)
    {
        try {
            $path = str_replace('/storage/', '', $video->url);
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            $video->delete();
            return response()->json(['success' => true, 'message' => 'Vidéo supprimée']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}

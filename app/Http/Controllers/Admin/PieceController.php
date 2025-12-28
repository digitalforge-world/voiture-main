<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PieceDetachee;
use App\Models\PhotoPiece;
use App\Models\VideoPiece;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PieceController extends Controller
{
    public function index(Request $request)
    {
        $query = PieceDetachee::query()->with(['photos', 'videos']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                    ->orWhere('reference', 'LIKE', "%{$search}%")
                    ->orWhere('marque_compatible', 'LIKE', "%{$search}%");
            });
        }

        if ($category = $request->input('category')) {
            $query->where('categorie', $category);
        }

        $pieces = $query->latest()->paginate(15)->withQueryString();
        return view('admin.parts.inventory.index', compact('pieces'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:150',
            'reference' => 'required|string|max:50|unique:pieces_detachees,reference',
            'marque_compatible' => 'required|string|max:100',
            'modele_compatible' => 'nullable|string|max:150',
            'categorie' => 'required|in:moteur,transmission,suspension,freinage,carrosserie,electricite,interieur,pneumatique,optique_eclairage,echappement,refroidissement,filtration,embrayage,direction,climatisation,vitrage,accessoires,autre',
            'prix' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'etat' => 'required|in:neuf,occasion,reconditionne',
            'description' => 'nullable|string',
            'photos.*' => 'nullable|image|max:2048',
            'videos.*' => 'nullable|mimetypes:video/mp4,video/quicktime|max:20480',
        ]);

        $piece = PieceDetachee::create($validated);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $file) {
                $path = $file->store('pieces', 'public');
                $url = '/storage/' . $path;

                // First image is principal by default
                $isPrincipal = ($index === 0);

                PhotoPiece::create([
                    'piece_id' => $piece->id,
                    'url' => $url,
                    'ordre' => $index + 1,
                    'principale' => $isPrincipal
                ]);

                if ($isPrincipal) {
                    $piece->update(['image' => $url]);
                }
            }
        }

        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $index => $file) {
                $path = $file->store('pieces/videos', 'public');
                $url = '/storage/' . $path;

                VideoPiece::create([
                    'piece_id' => $piece->id,
                    'url' => $url,
                    'ordre' => $index + 1
                ]);
            }
        }

        return redirect()->route('admin.parts-inventory.index')->with('success', 'Pièce ajoutée au catalogue multimédia.');
    }

    public function update(Request $request, PieceDetachee $parts_inventory)
    {
        // For update, the parameter name matches the resource name in the route (parts-inventory -> $parts_inventory)
        $piece = $parts_inventory;

        $validated = $request->validate([
            'nom' => 'required|string|max:150',
            'reference' => 'required|string|max:50|unique:pieces_detachees,reference,' . $piece->id,
            'marque_compatible' => 'required|string|max:100',
            'modele_compatible' => 'nullable|string|max:150',
            'categorie' => 'required|in:moteur,transmission,suspension,freinage,carrosserie,electricite,interieur,pneumatique,optique_eclairage,echappement,refroidissement,filtration,embrayage,direction,climatisation,vitrage,accessoires,autre',
            'prix' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'etat' => 'required|in:neuf,occasion,reconditionne',
            'description' => 'nullable|string',
            'photos.*' => 'nullable|image|max:2048',
            'videos.*' => 'nullable|mimetypes:video/mp4,video/quicktime|max:20480',
        ]);

        $piece->update($validated);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $file) {
                $path = $file->store('pieces', 'public');
                $url = '/storage/' . $path;

                PhotoPiece::create([
                    'piece_id' => $piece->id,
                    'url' => $url,
                    'ordre' => $piece->photos()->count() + 1,
                    'principale' => false
                ]);
            }
        }

        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $index => $file) {
                $path = $file->store('pieces/videos', 'public');
                $url = '/storage/' . $path;

                VideoPiece::create([
                    'piece_id' => $piece->id,
                    'url' => $url,
                    'ordre' => $piece->videos()->count() + 1
                ]);
            }
        }

        return redirect()->route('admin.parts-inventory.index')->with('success', 'Catalogue multimédia mis à jour.');
    }

    public function destroy(PieceDetachee $parts_inventory)
    {
        $piece = $parts_inventory;
        // Delete physical files
        foreach ($piece->photos as $photo) {
            $path = str_replace('/storage/', '', $photo->url);
            Storage::disk('public')->delete($path);
        }

        foreach ($piece->videos as $video) {
            $path = str_replace('/storage/', '', $video->url);
            Storage::disk('public')->delete($path);
        }
        $piece->delete();
        return redirect()->route('admin.parts-inventory.index')->with('success', 'Pièce retirée du catalogue.');
    }
}

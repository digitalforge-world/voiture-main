<?php

namespace App\Http\Controllers;

use App\Models\CarViewer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CarViewerController extends Controller
{
    // ──────────────────────────────────────────────
    //  GET /viewer/{slug}  →  affiche le viewer 360°
    // ──────────────────────────────────────────────
    public function show(string $slug)
    {
        $car = CarViewer::where('slug', $slug)->firstOrFail();

        // ✅ Lire les fichiers réels depuis frames_path (utilise enfin frames_path)
        $rawFiles = Storage::disk('public')->files($car->frames_path);

        // ✅ Filtrer uniquement les images frame_XXX et trier par nom
        $frames = collect($rawFiles)
            ->filter(fn($f) => preg_match('/frame_\d+\.(jpg|jpeg|png|webp)$/i', basename($f)))
            ->sortBy(fn($f) => basename($f))
            ->values()
            ->map(fn($f) => Storage::url($f))
            ->toArray();

        if (empty($frames)) {
            abort(404, 'Aucune frame trouvée pour ce viewer 360°.');
        }

        return view('viewer360.show', compact('car', 'frames'));
    }

    // ──────────────────────────────────────────────
    //  GET /viewer/create  →  formulaire d'upload
    // ──────────────────────────────────────────────
    public function create()
    {
        return view('viewer360.create');
    }

    // ──────────────────────────────────────────────
    //  POST /viewer  →  reçoit les photos + crée l'entrée
    // ──────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'frames'      => 'required|array|min:8|max:72',
            // ✅ Validation MIME stricte — Laravel vérifie le contenu binaire, pas juste l'extension
            'frames.*'    => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ], [
            'frames.min'     => 'Minimum 8 photos requises pour un viewer 360° fluide.',
            'frames.max'     => 'Maximum 72 photos acceptées.',
            'frames.*.image' => 'Chaque fichier doit être une image valide.',
            'frames.*.mimes' => 'Formats acceptés : JPG, PNG, WEBP.',
            'frames.*.max'   => 'Chaque photo ne doit pas dépasser 5 Mo.',
        ]);

        $slug   = Str::slug($request->name) . '-' . Str::random(6);
        $folder = "viewers/{$slug}";

        // ✅ Tri côté serveur par nom original (indépendant du tri JS)
        $files = $request->file('frames');
        usort($files, fn($a, $b) => strcmp($a->getClientOriginalName(), $b->getClientOriginalName()));

        $framesCount = count($files);
        $padLength   = strlen((string) $framesCount);

        // ✅ Transaction DB — si erreur, rollback ET suppression des fichiers
        DB::beginTransaction();
        try {
            foreach ($files as $index => $file) {
                // ✅ Conserver l'extension originale (corrige le bug .jpg forcé)
                $ext      = strtolower($file->getClientOriginalExtension()) ?: 'jpg';
                $pad      = str_pad($index + 1, $padLength, '0', STR_PAD_LEFT);
                $filename = "frame_{$pad}.{$ext}";

                $file->storeAs($folder, $filename, 'public');
            }

            $car = CarViewer::create([
                'name'        => $request->name,
                'slug'        => $slug,
                'description' => $request->description,
                'frame_count' => $framesCount,
                'frames_path' => $folder, // ✅ utilisé dans show() maintenant
            ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            // ✅ Nettoyage des fichiers orphelins si erreur DB
            Storage::disk('public')->deleteDirectory($folder);

            return back()
                ->withErrors(['frames' => 'Erreur lors de la création du viewer. Veuillez réessayer.'])
                ->withInput();
        }

        return redirect()
            ->route('viewer.show', $car->slug)
            ->with('success', "Viewer 360° créé avec {$framesCount} photos !");
    }

    // ──────────────────────────────────────────────
    //  GET /viewer  →  liste tous les viewers
    // ──────────────────────────────────────────────
    public function index()
    {
        $cars = CarViewer::latest()->paginate(12);
        return view('viewer360.index', compact('cars'));
    }

    // ──────────────────────────────────────────────
    //  DELETE /viewer/{slug}
    // ──────────────────────────────────────────────
    public function destroy(string $slug)
    {
        $car = CarViewer::where('slug', $slug)->firstOrFail();

        // ✅ Utilise frames_path pour cibler le bon dossier
        Storage::disk('public')->deleteDirectory($car->frames_path);
        $car->delete();

        return redirect()
            ->route('viewer.index')
            ->with('success', 'Viewer 360° supprimé avec succès.');
    }
}

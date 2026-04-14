<?php

namespace App\Http\Controllers;

use App\Models\PieceDetachee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PieceController extends Controller
{
    public function index(Request $request)
    {
        $query = PieceDetachee::query();

        if ($request->has('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('nom', 'like', '%' . $request->q . '%')
                    ->orWhere('reference', 'like', '%' . $request->q . '%')
                    ->orWhere('marque_compatible', 'like', '%' . $request->q . '%');
            });
        }

        if ($request->has('categorie')) {
            $query->where('categorie', $request->categorie);
        }

        $pieces = $query->where('disponible', true)
            ->latest()
            ->paginate(12);

        $categories = ['moteur', 'transmission', 'suspension', 'freinage', 'carrosserie', 'electricite', 'interieur', 'pneumatique', 'optique_eclairage', 'filtration'];

        return view('parts.index', compact('pieces', 'categories'));
    }

    public function searchCompatibility(Request $request)
    {
        $request->validate([
            'marque' => 'required',
            'modele' => 'required',
            'annee' => 'required|numeric',
        ]);

        // Using the stored procedure defined in db.sql (if available) or manual query
        // For simplicity with Eloquent:
        $pieces = PieceDetachee::where('marque_compatible', $request->marque)
            ->where(function ($q) use ($request) {
                $q->where('modele_compatible', 'like', '%' . $request->modele . '%')
                    ->orWhereNull('modele_compatible');
            })
            ->where(function ($q) use ($request) {
                $q->where('annee_debut', '<=', $request->annee)
                    ->orWhereNull('annee_debut');
            })
            ->where(function ($q) use ($request) {
                $q->where('annee_fin', '>=', $request->annee)
                    ->orWhereNull('annee_fin');
            })
            ->get();

        return view('parts.compatibility_results', [
            'pieces' => $pieces,
            'search' => $request->all()
        ]);
    }

    public function buy(Request $request, $id)
    {
        $piece = PieceDetachee::findOrFail($id);

        if ($piece->stock <= 0) {
            return back()->with('error', 'Cette pièce est en rupture de stock.');
        }

        $request->validate([
            'client_nom' => 'required|string|max:255',
            'client_telephone' => 'required|string|max:20',
        ]);

        // Generate Tracking Number
        $trackingNumber = \App\Helpers\TrackingHelper::forPart();

        $commande = \App\Models\CommandePiece::create([
            'user_id' => null, // Anonymous
            'tracking_number' => $trackingNumber,
            'client_nom' => $request->client_nom,
            'client_telephone' => $request->client_telephone,
            // 'client_email' => $request->client_email, // Not yet in form
            'montant_total' => $piece->prix,
            'statut' => 'en_attente',
            'type_livraison' => 'standard',
            'adresse_livraison' => 'A définir',
            'frais_livraison' => 2500,
            'reference' => 'CP-' . strtoupper(\Illuminate\Support\Str::random(8)),
            'date_commande' => now(),
        ]);

        \App\Models\LigneCommandePiece::create([
            'commande_piece_id' => $commande->id,
            'piece_id' => $piece->id,
            'quantite' => 1,
            'prix_unitaire' => $piece->prix,
        ]);

        // Stock update is handled by DB trigger after_insert_ligne_commande_piece

        return redirect()->route('tracking.success')->with('tracking_number', $trackingNumber);
    }

    public function apiCheckout(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'client_nom' => 'required|string|max:255',
            'client_telephone' => 'required|string|max:20',
        ]);

        $trackingNumber = \App\Helpers\TrackingHelper::forPart();
        $total = 0;

        foreach($request->items as $item) {
            $total += $item['prix'];
        }

        try {
            return DB::transaction(function () use ($request, $trackingNumber, $total) {
                // Insertion de la commande parente
                $commandeId = DB::table('commandes_pieces')->insertGetId([
                    'reference' => 'CP-' . strtoupper(\Illuminate\Support\Str::random(8)),
                    'tracking_number' => $trackingNumber,
                    'client_nom' => $request->client_nom,
                    'client_telephone' => $request->client_telephone,
                    'montant_total' => $total,
                    'statut' => 'en_attente',
                    'date_commande' => now(),
                    'frais_livraison' => 0,
                    'type_livraison' => 'retrait',
                    'adresse_livraison' => 'A définir'
                ]);

                // Insertion des lignes de commande
                foreach ($request->items as $item) {
                    DB::table('lignes_commandes_pieces')->insert([
                        'commande_piece_id' => $commandeId,
                        'piece_id' => $item['id'],
                        'quantite' => 1,
                        'prix_unitaire' => $item['prix'],
                        'montant_ligne' => $item['prix'],
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'tracking_number' => $trackingNumber
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'enregistrement : ' . $e->getMessage()
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Voiture;
use App\Models\VoitureLocation;
use App\Models\PieceDetachee;
use Illuminate\Http\Request;

class GlobalSearchController extends Controller
{
    public function search(Request $request)
    {
        $q = $request->query('q');
        if (!$q || strlen($q) < 2) {
            return response()->json(['results' => []]);
        }

        // Search in Imports (Added Year, Origin, and simplified search)
        $imports = Voiture::where(function($query) use ($q) {
                $query->where('marque', 'LIKE', "%$q%")
                      ->orWhere('modele', 'LIKE', "%$q%")
                      ->orWhere('annee', 'LIKE', "%$q%")
                      ->orWhere('numero_chassis', 'LIKE', "%$q%")
                      ->orWhere('ville_origine', 'LIKE', "%$q%");
            })
            ->limit(5)
            ->get()
            ->map(function($v) {
                return [
                    'type' => 'Achat',
                    'title' => $v->marque . ' ' . $v->modele . ' (' . $v->annee . ')',
                    'subtitle' => 'Importation depuis ' . $v->pays_origine,
                    'price' => number_format($v->prix, 0, ',', ' ') . ' FCFA',
                    'img' => $v->photo_principale ?? 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&q=80&w=200',
                    'url' => route('cars.show', $v->id),
                    'color' => 'amber'
                ];
            });

        // Search in Rentals (Added categories and availability)
        $rentals = VoitureLocation::where('disponible', true)
            ->where(function($query) use ($q) {
                $query->where('marque', 'LIKE', "%$q%")
                      ->orWhere('modele', 'LIKE', "%$q%")
                      ->orWhere('categorie', 'LIKE', "%$q%")
                      ->orWhere('immatriculation', 'LIKE', "%$q%")
                      ->orWhere('annee', 'LIKE', "%$q%");
            })
            ->limit(5)
            ->get()
            ->map(function($v) {
                return [
                    'type' => 'Location',
                    'title' => $v->marque . ' ' . $v->modele,
                    'subtitle' => ucfirst($v->categorie) . ' • ' . $v->transmission,
                    'price' => number_format($v->prix_jour, 0, ',', ' ') . ' FCFA / jour',
                    'img' => $v->photo_principale ?? 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&q=80&w=200',
                    'url' => route('rental.index', ['category' => $v->categorie]),
                    'color' => 'blue'
                ];
            });

        // Search in Parts
        $pieces = PieceDetachee::where('nom', 'LIKE', "%$q%")
            ->orWhere('reference', 'LIKE', "%$q%")
            ->limit(5)
            ->get()
            ->map(function($p) {
                return [
                    'type' => 'Pièce',
                    'title' => $p->nom,
                    'subtitle' => 'Réf: ' . $p->reference,
                    'price' => number_format($p->prix, 0, ',', ' ') . ' FCFA',
                    'img' => 'https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?auto=format&fit=crop&q=80&w=200',
                    'url' => route('parts.index'),
                    'color' => 'emerald'
                ];
            });

        // Tracking Number Lookup (Public side only tracking access)
        $tracking = [];
        $upperQ = strtoupper($q);
        if (str_starts_with($upperQ, 'CV-') || str_starts_with($upperQ, 'LOC-') || str_starts_with($upperQ, 'TRK-')) {
            $tracking = [[
                'type' => 'Suivi',
                'title' => 'Suivre ma commande : ' . $upperQ,
                'subtitle' => 'Cliquez pour voir l\'état actuel',
                'price' => 'Accès Suivi',
                'img' => null,
                'url' => route('tracking.index'),
                'color' => 'rose'
            ]];
        }

        $results = $imports->concat($rentals)->concat($pieces)->concat($tracking);

        return response()->json([
            'results' => $results->values()->all()
        ]);
    }
}

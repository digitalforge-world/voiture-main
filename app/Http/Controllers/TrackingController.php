<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\TrackingHelper;

class TrackingController extends Controller
{
    /**
     * Affiche le formulaire de suivi
     */
    public function index()
    {
        return view('tracking.index');
    }

    /**
     * Recherche une commande par numéro de tracking
     */
    public function track(Request $request)
    {
        $request->validate([
            'tracking_number' => 'required|string|min:10|max:20'
        ]);

        $tracking = strtoupper(trim($request->tracking_number));

        if (!TrackingHelper::isValid($tracking)) {
            return back()
                ->withInput()
                ->with('error', 'Format de numéro de tracking invalide. Format attendu: XXX-YYYY-ZZZZ (ex: REV-2024-A3B7)');
        }

        $type = TrackingHelper::getType($tracking);

        if (!$type) {
            return back()
                ->withInput()
                ->with('error', 'Type de service non reconnu. Vérifiez que le numéro commence par CAR-, LOC-, PCE- ou REV-');
        }

        $order = null;

        // Rechercher selon le type
        switch ($type) {
            case 'voiture':
                $order = DB::table('commandes_voitures')
                    ->join('voitures', 'commandes_voitures.voiture_id', '=', 'voitures.id')
                    ->where('commandes_voitures.tracking_number', $tracking)
                    ->select(
                        'commandes_voitures.*',
                        'voitures.marque',
                        'voitures.modele',
                        'voitures.annee',
                        'voitures.prix',
                        'voitures.image'
                    )
                    ->first();
                break;

            case 'location':
                $order = DB::table('locations')
                    ->join('voitures_location', 'locations.voiture_location_id', '=', 'voitures_location.id')
                    ->where('locations.tracking_number', $tracking)
                    ->select(
                        'locations.*',
                        'voitures_location.marque',
                        'voitures_location.modele',
                        'voitures_location.photo_principale as image'
                    )
                    ->first();
                break;

            case 'piece':
                $order = DB::table('commandes_pieces')
                    ->join('ligne_commandes_pieces', 'commandes_pieces.id', '=', 'ligne_commandes_pieces.commande_piece_id')
                    ->join('pieces_detachees', 'ligne_commandes_pieces.piece_id', '=', 'pieces_detachees.id')
                    ->where('commandes_pieces.tracking_number', $tracking)
                    ->select(
                        'commandes_pieces.*',
                        'pieces_detachees.nom as nom_piece',
                        'pieces_detachees.reference as ref_piece',
                        'ligne_commandes_pieces.quantite'
                    )
                    ->first();
                break;

            case 'revision':
                $order = DB::table('revisions')
                    ->where('revisions.tracking_number', $tracking)
                    // Revisions store vehicle info directly, no join needed if no voiture_id
                    ->first();
                break;

            default:
                return back()
                    ->withInput()
                    ->with('error', 'Type de service non reconnu.');
        }

        if (!$order) {
            return back()
                ->withInput()
                ->with('error', "Aucune commande trouvée avec ce numéro de tracking. Vérifiez que vous avez bien entré le numéro complet (ex: $tracking)");
        }

        return view('tracking.show', [
            'order' => $order,
            'type' => $type,
            'tracking' => $tracking
        ]);
    }

    /**
     * Génère et télécharge le PDF du tracking
     */
    public function downloadPdf(Request $request)
    {
        $request->validate([
            'tracking_number' => 'required|string|min:10|max:20',
            'type' => 'required|string|in:voiture,location,piece,revision'
        ]);

        $tracking = strtoupper(trim($request->tracking_number));
        $type = $request->type;
        $order = null;

        // Rechercher selon le type
        switch ($type) {
            case 'voiture':
                $order = \DB::table('commandes_voitures')
                    ->join('voitures', 'commandes_voitures.voiture_id', '=', 'voitures.id')
                    ->where('commandes_voitures.tracking_number', $tracking)
                    ->select('commandes_voitures.*', 'voitures.marque', 'voitures.modele', 'voitures.annee', 'voitures.prix', 'voitures.image')
                    ->first();
                break;

            case 'location':
                $order = \DB::table('locations')
                    ->join('voitures', 'locations.voiture_id', '=', 'voitures.id')
                    ->where('locations.tracking_number', $tracking)
                    ->select('locations.*', 'voitures.marque', 'voitures.modele', 'voitures.annee', 'voitures.image')
                    ->first();
                break;

            case 'piece':
                $order = \DB::table('commandes_pieces')
                    ->join('ligne_commandes_pieces', 'commandes_pieces.id', '=', 'ligne_commandes_pieces.commande_piece_id')
                    ->join('pieces_detachees', 'ligne_commandes_pieces.piece_id', '=', 'pieces_detachees.id')
                    ->where('commandes_pieces.tracking_number', $tracking)
                    ->select('commandes_pieces.*', 'pieces_detachees.nom as nom_piece', 'pieces_detachees.reference as ref_piece', 'ligne_commandes_pieces.quantite')
                    ->first();
                break;

            case 'revision':
                $order = \DB::table('revisions')
                    ->where('revisions.tracking_number', $tracking)
                    ->first();
                break;
        }

        if (!$order) {
            return back()->with('error', 'Commande non trouvée');
        }

        // Générer le PDF
        $pdf = \PDF::loadView('tracking.pdf', [
            'order' => $order,
            'type' => $type,
            'tracking' => $tracking
        ]);

        // Nom du fichier
        $filename = $tracking . '_' . date('Ymd_His') . '.pdf';

        // Télécharger
        return $pdf->download($filename);
    }
}

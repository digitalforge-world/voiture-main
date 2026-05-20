<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Port;
use App\Models\Voiture;
use App\Models\VoitureLocation;
use App\Models\PieceDetachee;
use App\Models\CommandeVoiture;
use App\Models\Location;
use App\Models\CommandePiece;
use App\Models\Revision;

try {
    $admin = User::where('role', 'admin')->first();
    $voiture = Voiture::first();
    $port = Port::first();
    $locVoiture = VoitureLocation::first();
    $piece = PieceDetachee::first();

    // 1. Commande Voiture
    CommandeVoiture::updateOrCreate(
        ['tracking_number' => 'CAR-2024-X8Y9'],
        [
            'reference' => 'CMD-CAR-0001',
            'user_id' => $admin ? $admin->id : null,
            'voiture_id' => $voiture ? $voiture->id : null,
            'port_destination_id' => $port ? $port->id : null,
            'prix_voiture' => 15000000,
            'frais_import' => 500000,
            'frais_port' => 200000,
            'frais_douane' => 1500000,
            'autres_frais' => 100000,
            'montant_total' => 17300000,
            'acompte_verse' => 10000000,
            'reste_a_payer' => 7300000,
            'statut' => 'en_transit', 
            'date_commande' => now()->subDays(10),
            'date_confirmation' => now()->subDays(9),
            'date_expedition' => now()->subDays(5),
            'date_livraison_estimee' => now()->addDays(20),
            'client_nom' => 'Jean Dupont',
            'client_email' => 'jean.dupont@example.com',
            'client_telephone' => '+228 90 12 34 56',
            'notes' => 'Commande urgente, s\'assurer du bon état cosmétique du véhicule.'
        ]
    );

    // 2. Location
    Location::updateOrCreate(
        ['tracking_number' => 'LOC-2024-A1B2'],
        [
            'reference' => 'LOC-0001',
            'user_id' => $admin ? $admin->id : null,
            'voiture_location_id' => $locVoiture ? $locVoiture->id : null,
            'date_debut' => now()->addDays(2),
            'date_fin' => now()->addDays(7),
            'montant_location' => 125000,
            'caution' => 100000,
            'montant_total' => 225000,
            'statut' => 'confirme', 
            'client_nom' => 'Alice Martin',
            'client_email' => 'alice.martin@example.com',
            'client_telephone' => '+228 91 23 45 67',
            'commentaires' => 'Demande de livraison à domicile du véhicule.'
        ]
    );

    // 3. Commande Piece
    CommandePiece::updateOrCreate(
        ['tracking_number' => 'PCE-2024-C3D4'],
        [
            'reference' => 'CMD-PCE-0001',
            'user_id' => $admin ? $admin->id : null,
            'montant_total' => 5000,
            'statut' => 'en_preparation', 
            'type_livraison' => 'livraison',
            'adresse_livraison' => 'Quartier Deckon, Lomé, Togo',
            'frais_livraison' => 1000,
            'date_commande' => now()->subDays(2),
            'client_nom' => 'Bob Marley',
            'client_email' => 'bob@reggae.com',
            'client_telephone' => '+228 92 34 56 78',
            'notes' => 'Merci de livrer en fin d\'après-midi.'
        ]
    );

    // 4. Revision
    Revision::updateOrCreate(
        ['tracking_number' => 'REV-2024-E5F6'],
        [
            'reference' => 'REV-0001',
            'user_id' => $admin ? $admin->id : null,
            'marque_vehicule' => 'Toyota',
            'modele_vehicule' => 'Corolla',
            'annee_vehicule' => 2018,
            'immatriculation' => 'TG-9999-AZ',
            'kilometrage' => 120000,
            'probleme_description' => 'Bruit suspect à la suspension avant gauche et vidange moteur complète.',
            'type_revision' => 'entretien',
            'diagnostic' => 'Amortisseur avant gauche fatigué, filtre à huile encrassé.',
            'interventions_prevues' => 'Remplacement de l\'amortisseur et kit de vidange complet.',
            'pieces_necessaires' => 'Amortisseur avant gauche, Huile 5W30, Filtre à huile.',
            'montant_devis' => 95000,
            'montant_final' => 95000,
            'statut' => 'accepte', 
            'date_demande' => now()->subDays(3),
            'date_diagnostic' => now()->subDays(2),
            'date_devis' => now()->subDays(2),
            'client_nom' => 'Charlie Brown',
            'client_email' => 'charlie@peanuts.com',
            'client_telephone' => '+228 93 45 67 89'
        ]
    );

    echo "Orders Seeded Successfully.\n";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

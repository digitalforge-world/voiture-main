<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

/**
 * Script de test pour créer une révision et obtenir le tracking number
 *
 * Utilisation:
 * php create_test_revision.php
 */

use App\Models\Revision;
use App\Helpers\TrackingHelper;
use Illuminate\Support\Str;

// Générer le tracking number
$tracking = TrackingHelper::forRevision();

// Créer la révision
$revision = Revision::create([
    'tracking_number' => $tracking,
    'reference' => 'REV-' . strtoupper(Str::random(8)),
    'marque_vehicule' => 'Toyota',
    'modele_vehicule' => 'Corolla',
    'annee_vehicule' => 2020,
    'immatriculation' => 'AB-123-CD',
    'kilometrage' => 45000,
    'probleme_description' => 'Bruit anormal au niveau du moteur lors de l\'accélération. Le bruit apparaît surtout à partir de 80 km/h.',
    'type_revision' => 'complete',
    'statut' => 'en_attente',
    'client_nom' => 'Jean Dupont',
    'client_email' => 'jean.dupont@test.com',
    'client_telephone' => '+237 690 12 34 56'
]);

echo "\n";
echo "✅ Révision créée avec succès!\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📋 ID: " . $revision->id . "\n";
echo "🔢 Tracking Number: " . $tracking . "\n";
echo "📝 Reference: " . $revision->reference . "\n";
echo "👤 Client: " . $revision->client_nom . "\n";
echo "🚗 Véhicule: " . $revision->marque_vehicule . " " . $revision->modele_vehicule . "\n";
echo "📊 Statut: " . $revision->statut . "\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "\n";
echo "🔍 Pour tester:\n";
echo "1. Allez sur: http://localhost:8000/tracking\n";
echo "2. Entrez ce numéro: " . $tracking . "\n";
echo "3. Cliquez 'Rechercher ma Commande'\n";
echo "\n";

exit;

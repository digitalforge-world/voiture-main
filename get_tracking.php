<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;

foreach(['commandes_voitures', 'locations', 'commandes_pieces', 'revisions'] as $t) {
    try {
        $cols = Schema::getColumnListing($t);
        echo "Table $t cols: " . implode(', ', $cols) . "\n";
    } catch (\Exception $e) {
        echo "Error listing $t: " . $e->getMessage() . "\n";
    }
}

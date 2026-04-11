<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$cars = \App\Models\Voiture::all();
$fixedCount = 0;

foreach ($cars as $car) {
    if (empty($car->slug)) {
        $car->slug = \App\Models\Voiture::generateUniqueSlug($car);
        $car->save();
        $fixedCount++;
    }
}

echo "Total cars checked: " . $cars->count() . "\n";
echo "Total slugs fixed: " . $fixedCount . "\n";

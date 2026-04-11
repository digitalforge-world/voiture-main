<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('voitures', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('id');
        });

        // Génère un slug pour les véhicules existants
        $voitures = \App\Models\Voiture::all();
        foreach ($voitures as $voiture) {
            $base = Str::slug($voiture->marque . '-' . $voiture->modele . '-' . $voiture->annee);
            $suffix = Str::lower(Str::random(6));
            $voiture->slug = $base . '-' . $suffix;
            $voiture->save();
        }
    }

    public function down(): void
    {
        Schema::table('voitures', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};

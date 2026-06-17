<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('voitures', function (Blueprint $table) {
            // Ajouter la colonne seulement si elle n'existe pas encore
            if (!Schema::hasColumn('voitures', 'categorie')) {
                $table->string('categorie', 20)->default('voiture')->after('type_vehicule');
                $table->index('categorie');
            }
        });

        // Pour MySQL uniquement: étendre l'ENUM type_vehicule pour inclure 'scooter'
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE voitures MODIFY COLUMN type_vehicule ENUM(
                'berline','suv','4x4','pickup','utilitaire','coupe','break','scooter'
            ) DEFAULT 'berline'");
        }
    }

    public function down(): void
    {
        Schema::table('voitures', function (Blueprint $table) {
            if (Schema::hasColumn('voitures', 'categorie')) {
                $table->dropIndex(['categorie']);
                $table->dropColumn('categorie');
            }
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE voitures MODIFY COLUMN type_vehicule ENUM(
                'berline','suv','4x4','pickup','utilitaire','coupe','break'
            ) DEFAULT 'berline'");
        }
    }
};

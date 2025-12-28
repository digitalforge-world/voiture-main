<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Enrich the parts table with more technical categories
        Schema::table('pieces_detachees', function (Blueprint $table) {
            // Since altering an enum can be tricky with some drivers, we'll use a more flexible approach if needed,
            // but for a typical Laravel MySQL/PostgreSQL setup, we can re-specify the enum.
            // Note: DB::statement is safer for enums on some platforms.
        });

        // Use raw SQL for parts enum update to ensure compatibility
        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'mysql' || $driver === 'mariadb') {
            DB::statement("ALTER TABLE pieces_detachees MODIFY COLUMN categorie ENUM('moteur','transmission','suspension','freinage','carrosserie','electricite','interieur','pneumatique','optique_eclairage','echappement','refroidissement','filtration','embrayage','direction','climatisation','vitrage','accessoires','autre') NOT NULL");
        }

        // Enrich the cars table with more detailed technical and market status fields
        Schema::table('voitures', function (Blueprint $table) {
            // Technical Specs
            $table->string('consommation_mixte', 20)->nullable()->after('options_equipements');
            $table->string('emission_co2', 20)->nullable()->after('consommation_mixte');
            $table->string('vitesse_max', 20)->nullable()->after('emission_co2');
            $table->string('acceleration_0_100', 20)->nullable()->after('vitesse_max');
            $table->string('couple_moteur', 30)->nullable()->after('acceleration_0_100');
            $table->string('capacite_reservoir', 20)->nullable()->after('couple_moteur');
            $table->string('poids_a_vide', 20)->nullable()->after('capacite_reservoir');

            // Market & History
            $table->string('origine_marche', 50)->nullable()->after('poids_a_vide'); // e.g., Europe, US, GCC, Local
            $table->tinyInteger('nombre_proprietaires')->default(1)->after('origine_marche');
            $table->boolean('carnet_entretien_ajour')->default(false)->after('nombre_proprietaires');
            $table->boolean('non_fumeur')->default(false)->after('carnet_entretien_ajour');
            $table->string('classe_environnementale', 20)->nullable()->after('non_fumeur'); // Euro 6, etc.

            // Structured Equipment Data
            $table->json('equipements_details')->nullable()->after('classe_environnementale');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('voitures', function (Blueprint $table) {
            $table->dropColumn([
                'consommation_mixte',
                'emission_co2',
                'vitesse_max',
                'acceleration_0_100',
                'couple_moteur',
                'capacite_reservoir',
                'poids_a_vide',
                'origine_marche',
                'nombre_proprietaires',
                'carnet_entretien_ajour',
                'non_fumeur',
                'classe_environnementale',
                'equipements_details'
            ]);
        });

        // Revert enum if necessary (though usually down avoids destructive changes like this if possible)
        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'mysql' || $driver === 'mariadb') {
            DB::statement("ALTER TABLE pieces_detachees MODIFY COLUMN categorie ENUM('moteur','transmission','suspension','freinage','carrosserie','electricite','interieur','pneumatique','autre') NOT NULL");
        }
    }
};

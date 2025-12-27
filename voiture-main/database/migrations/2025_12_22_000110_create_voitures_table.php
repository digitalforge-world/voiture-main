<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voitures', function (Blueprint $table) {
            $table->id();
            $table->string('marque', 50);
            $table->string('modele', 100);
            $table->year('annee');
            $table->unsignedInteger('kilometrage')->nullable();
            $table->decimal('prix', 12, 2);
            $table->string('pays_origine', 50);
            $table->string('ville_origine', 100)->nullable();
            $table->enum('etat', ['neuf','occasion','excellent','bon','moyen'])->default('occasion');
            $table->string('moteur',50)->nullable();
            $table->string('cylindree',20)->nullable();
            $table->string('puissance',20)->nullable();
            $table->enum('carburant', ['essence','diesel','hybride','electrique','gpl'])->default('essence');
            $table->enum('transmission', ['manuelle','automatique','semi-automatique'])->default('manuelle');
            $table->string('couleur',30)->nullable();
            $table->tinyInteger('nombre_portes')->nullable();
            $table->tinyInteger('nombre_places')->nullable();
            $table->enum('disponibilite', ['disponible','reserve','vendu','en_transit'])->default('disponible');
            $table->enum('type_vehicule', ['berline','suv','4x4','pickup','utilitaire','coupe','break'])->default('berline');
            $table->text('description')->nullable();
            $table->text('options_equipements')->nullable();
            $table->string('numero_chassis',50)->nullable();
            $table->foreignId('port_recommande_id')->nullable()->constrained('ports')->nullOnDelete();
            $table->timestamps();
            $table->index('marque');
            $table->index('disponibilite');
            $table->index('pays_origine');
            $table->index('prix');
            $table->index('annee');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voitures');
    }
};

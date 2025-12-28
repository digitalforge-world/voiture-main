<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voitures_location', function (Blueprint $table) {
            $table->id();
            $table->string('marque',50);
            $table->string('modele',100);
            $table->year('annee');
            $table->string('immatriculation',30)->unique();
            $table->string('couleur',30)->nullable();
            $table->unsignedInteger('kilometrage')->nullable();
            $table->enum('transmission', ['manuelle','automatique'])->default('manuelle');
            $table->enum('carburant', ['essence','diesel'])->default('essence');
            $table->tinyInteger('nombre_places')->nullable();
            $table->decimal('prix_jour', 8, 2);
            $table->decimal('caution', 10, 2);
            $table->boolean('disponible')->default(true);
            $table->enum('categorie', ['economique','confort','premium','suv','utilitaire'])->default('economique');
            $table->text('description')->nullable();
            $table->text('equipements')->nullable();
            $table->string('photo_principale',255)->nullable();
            $table->enum('etat_general', ['excellent','bon','moyen'])->default('bon');
            $table->timestamps();
            $table->index('disponible');
            $table->index('categorie');
            $table->index('prix_jour');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voitures_location');
    }
};

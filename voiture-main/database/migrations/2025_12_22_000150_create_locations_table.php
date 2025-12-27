<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 20)->unique()->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('tracking_number',40)->unique()->nullable();
            $table->foreignId('voiture_location_id')->constrained('voitures_location')->restrictOnDelete();
            $table->date('date_debut');
            $table->date('date_fin');
            $table->timestamp('date_debut_reelle')->nullable();
            $table->timestamp('date_fin_reelle')->nullable();
            $table->decimal('montant_location', 10, 2);
            $table->decimal('caution', 10, 2);
            $table->decimal('frais_supplementaires', 10, 2)->default(0);
            $table->decimal('montant_total', 10, 2);
            $table->enum('statut', ['reserve','confirme','en_cours','termine','annule'])->default('reserve');
            $table->text('etat_depart')->nullable();
            $table->unsignedInteger('kilometrage_depart')->nullable();
            $table->text('etat_retour')->nullable();
            $table->unsignedInteger('kilometrage_retour')->nullable();
            $table->text('commentaires')->nullable();
            $table->timestamp('date_reservation')->useCurrent();
            $table->timestamp('date_modification')->useCurrent()->useCurrentOnUpdate();
            $table->index('reference');
            $table->index('tracking_number');
            $table->index('user_id');
            $table->index('statut');
            $table->index(['date_debut','date_fin']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};

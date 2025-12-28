<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('revisions', function (Blueprint $table) {
            $table->id();
            $table->string('reference',20)->unique();
            $table->string('tracking_number',40)->unique()->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('marque_vehicule',50);
            $table->string('modele_vehicule',100);
            $table->year('annee_vehicule')->nullable();
            $table->string('immatriculation',30)->nullable();
            $table->unsignedInteger('kilometrage')->nullable();
            $table->text('probleme_description');
            $table->enum('type_revision', ['entretien','reparation','diagnostic','complete'])->default('complete');
            $table->text('diagnostic')->nullable();
            $table->text('interventions_prevues')->nullable();
            $table->text('pieces_necessaires')->nullable();
            $table->decimal('montant_devis',10,2)->default(0);
            $table->decimal('montant_final',10,2)->default(0);
            $table->enum('statut', ['en_attente','diagnostic_en_cours','devis_envoye','accepte','refuse','en_intervention','termine','annule'])->default('en_attente');
            $table->text('photos')->nullable();
            $table->timestamp('date_demande')->useCurrent();
            $table->timestamp('date_diagnostic')->nullable();
            $table->timestamp('date_devis')->nullable();
            $table->date('date_intervention_debut')->nullable();
            $table->date('date_intervention_fin')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('date_modification')->useCurrent()->useCurrentOnUpdate();
            $table->index('reference');
            $table->index('tracking_number');
            $table->index('user_id');
            $table->index('statut');
            $table->index('date_demande');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('revisions');
    }
};

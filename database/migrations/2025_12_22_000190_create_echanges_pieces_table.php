<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('echanges_pieces', function (Blueprint $table) {
            $table->id();
            $table->string('reference',20)->unique();
            $table->string('tracking_number',40)->unique()->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('piece_ancienne_nom',150);
            $table->text('piece_ancienne_description')->nullable();
            $table->enum('piece_ancienne_etat', ['bon','moyen','mauvais'])->default('moyen');
            $table->foreignId('piece_souhaitee_id')->nullable()->constrained('pieces_detachees')->nullOnDelete();
            $table->string('marque_vehicule',50)->nullable();
            $table->string('modele_vehicule',100)->nullable();
            $table->year('annee_vehicule')->nullable();
            $table->text('photos')->nullable();
            $table->enum('statut', ['en_attente','evalution','accepte','refuse','complete'])->default('en_attente');
            $table->decimal('rabais_propose', 8, 2)->default(0);
            $table->text('commentaire_admin')->nullable();
            $table->timestamp('date_demande')->useCurrent();
            $table->timestamp('date_evaluation')->nullable();
            $table->timestamp('date_modification')->useCurrent()->useCurrentOnUpdate();
            $table->index('reference');
            $table->index('tracking_number');
            $table->index('user_id');
            $table->index('statut');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('echanges_pieces');
    }
};

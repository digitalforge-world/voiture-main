<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commandes_voitures', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 20)->unique();
            $table->string('tracking_number', 40)->unique()->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('voiture_id')->constrained('voitures')->restrictOnDelete();
            $table->foreignId('port_destination_id')->constrained('ports')->restrictOnDelete();
            $table->decimal('prix_voiture', 12, 2);
            $table->decimal('frais_import', 10, 2)->default(0);
            $table->decimal('frais_port', 10, 2)->default(0);
            $table->decimal('frais_douane', 10, 2)->default(0);
            $table->decimal('autres_frais', 10, 2)->default(0);
            $table->decimal('montant_total', 12, 2);
            $table->decimal('acompte_verse', 12, 2)->default(0);
            $table->decimal('reste_a_payer', 12, 2);
            $table->enum('statut', ['en_attente','confirme','paiement_partiel','paye','en_transit','arrive','livre','annule'])->default('en_attente');
            $table->timestamp('date_commande')->useCurrent();
            $table->timestamp('date_confirmation')->nullable();
            $table->timestamp('date_paiement_complet')->nullable();
            $table->timestamp('date_expedition')->nullable();
            $table->date('date_livraison_estimee')->nullable();
            $table->date('date_livraison_reelle')->nullable();
            $table->text('notes')->nullable();
            $table->text('notes_admin')->nullable();
            $table->timestamp('date_modification')->useCurrent()->useCurrentOnUpdate();
            $table->index('reference');
            $table->index('user_id');
            $table->index('statut');
            $table->index('date_commande');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commandes_voitures');
    }
};

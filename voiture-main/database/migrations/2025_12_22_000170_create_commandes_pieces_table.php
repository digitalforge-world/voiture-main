<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commandes_pieces', function (Blueprint $table) {
            $table->id();
            $table->string('reference',20)->unique();
            $table->string('tracking_number',40)->unique()->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('montant_total', 10, 2);
            $table->enum('statut', ['en_attente','confirme','en_preparation','expedie','livre','annule'])->default('en_attente');
            $table->enum('type_livraison', ['retrait','livraison'])->default('retrait');
            $table->text('adresse_livraison')->nullable();
            $table->decimal('frais_livraison', 8, 2)->default(0);
            $table->timestamp('date_commande')->useCurrent();
            $table->date('date_livraison_estimee')->nullable();
            $table->date('date_livraison_reelle')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('date_modification')->useCurrent()->useCurrentOnUpdate();
            $table->index('reference');
            $table->index('tracking_number');
            $table->index('user_id');
            $table->index('statut');
            $table->index('date_commande');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commandes_pieces');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lignes_commandes_pieces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_piece_id')->constrained('commandes_pieces')->cascadeOnDelete();
            $table->foreignId('piece_id')->constrained('pieces_detachees')->restrictOnDelete();
            $table->unsignedInteger('quantite')->default(1);
            $table->decimal('prix_unitaire', 10, 2);
            $table->decimal('montant_ligne', 10, 2);
            $table->index('commande_piece_id');
            $table->index('piece_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lignes_commandes_pieces');
    }
};

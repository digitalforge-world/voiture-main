<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transport_conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_transport_id')
                  ->constrained('reservations_transport')
                  ->onDelete('cascade');
            $table->enum('auteur', ['client', 'admin', 'systeme'])->default('admin');
            $table->text('message');
            $table->enum('type', [
                'message',           // Message normal
                'proposition_prix',  // Admin propose un prix
                'confirmation_prix', // Client accepte le prix
                'notification_systeme', // Messages automatiques du système
            ])->default('message');
            $table->decimal('montant', 10, 2)->nullable(); // Pour les propositions de prix
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transport_conversations');
    }
};

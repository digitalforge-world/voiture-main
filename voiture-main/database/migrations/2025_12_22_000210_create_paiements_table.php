<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->string('reference',30)->unique();
            $table->string('tracking_number',40)->unique()->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('type_transaction', ['commande_voiture','location','commande_piece','revision','autre']);
            $table->unsignedBigInteger('transaction_id');
            $table->decimal('montant', 12, 2);
            $table->enum('methode', ['mobile_money','carte_bancaire','virement','especes','cheque'])->default('mobile_money');
            $table->string('operateur',50)->nullable();
            $table->string('numero_transaction_externe',100)->nullable();
            $table->enum('statut', ['en_attente','reussi','echoue','rembourse'])->default('en_attente');
            $table->timestamp('date_paiement')->useCurrent();
            $table->timestamp('date_confirmation')->nullable();
            $table->text('notes')->nullable();
            $table->index('reference');
            $table->index('tracking_number');
            $table->index('user_id');
            $table->index(['type_transaction','transaction_id']);
            $table->index('statut');
            $table->index('date_paiement');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};

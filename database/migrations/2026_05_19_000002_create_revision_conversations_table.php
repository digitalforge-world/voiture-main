<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('revision_conversations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('client_nom')->nullable();
            $table->string('client_telephone')->nullable();
            $table->string('client_email')->nullable();
            $table->string('marque_vehicule')->nullable();
            $table->string('modele_vehicule')->nullable();
            $table->string('annee_vehicule')->nullable();
            $table->json('messages')->nullable(); // Store the conversation flow
            $table->text('summary')->nullable(); // Store the finalized AI summary
            $table->boolean('is_closed')->default(false);
            $table->unsignedBigInteger('revision_id')->nullable();
            $table->timestamps();

            // Set up the relationship
            $table->foreign('revision_id')
                  ->references('id')
                  ->on('revisions')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revision_conversations');
    }
};

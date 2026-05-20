<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Photos pour les véhicules de location
        Schema::create('photos_voitures_location', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('voiture_location_id');
            $table->string('url');
            $table->integer('ordre')->default(0);
            $table->boolean('principale')->default(false);
            $table->timestamp('date_ajout')->useCurrent();

            $table->foreign('voiture_location_id')
                  ->references('id')
                  ->on('voitures_location')
                  ->onDelete('cascade');
        });

        // Photos pour les révisions (avant/après intervention)
        Schema::create('photos_revisions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('revision_id');
            $table->string('url');
            $table->integer('ordre')->default(0);
            $table->boolean('principale')->default(false);
            $table->enum('type', ['avant', 'apres', 'diagnostic', 'autre'])->default('autre');
            $table->timestamp('date_ajout')->useCurrent();

            $table->foreign('revision_id')
                  ->references('id')
                  ->on('revisions')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('photos_revisions');
        Schema::dropIfExists('photos_voitures_location');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('car_viewers', function (Blueprint $table) {
            $table->id();
            $table->string('name');                    // Nom du véhicule / produit
            $table->string('slug')->unique();           // URL friendly + unique
            $table->text('description')->nullable();
            $table->unsignedInteger('frame_count');    // ✅ unsigned — toujours positif
            $table->string('frames_path');             // Dossier : viewers/{slug}/
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_viewers');
    }
};

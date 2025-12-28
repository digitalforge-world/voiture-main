<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('photos_voitures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voiture_id')->constrained('voitures')->cascadeOnDelete();
            $table->string('url', 255);
            $table->tinyInteger('ordre')->default(1);
            $table->boolean('principale')->default(false);
            $table->timestamp('date_ajout')->useCurrent();
            $table->index('voiture_id');
            $table->index('principale');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('photos_voitures');
    }
};

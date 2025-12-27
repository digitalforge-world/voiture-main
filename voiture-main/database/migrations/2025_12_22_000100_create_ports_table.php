<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ports', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100);
            $table->string('code', 10)->unique();
            $table->string('pays', 100);
            $table->string('ville', 100);
            $table->enum('type', ['maritime', 'terrestre', 'mixte'])->default('maritime');
            $table->decimal('frais_base', 10, 2)->default(0);
            $table->integer('delai_moyen_jours')->default(30);
            $table->text('description')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
            $table->index('actif');
            $table->index('pays');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ports');
    }
};

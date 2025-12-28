<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('videos_voitures')) {
            Schema::create('videos_voitures', function (Blueprint $table) {
                $table->id();
                $table->foreignId('voiture_id')->constrained('voitures')->onDelete('cascade');
                $table->string('url');
                $table->integer('ordre')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('videos_pieces')) {
            Schema::create('videos_pieces', function (Blueprint $table) {
                $table->id();
                $table->foreignId('piece_id')->constrained('pieces_detachees')->onDelete('cascade');
                $table->string('url');
                $table->integer('ordre')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('videos_pieces');
        Schema::dropIfExists('videos_voitures');
    }
};

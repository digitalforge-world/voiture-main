<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pieces_detachees', function (Blueprint $table) {
            $table->id();
            $table->string('nom',150);
            $table->string('reference',50)->unique();
            $table->string('marque_compatible',100);
            $table->string('modele_compatible',150)->nullable();
            $table->year('annee_debut')->nullable();
            $table->year('annee_fin')->nullable();
            $table->string('moteur_compatible',50)->nullable();
            $table->string('numero_chassis_compatible',100)->nullable();
            $table->enum('categorie', ['moteur','transmission','suspension','freinage','carrosserie','electricite','interieur','pneumatique','autre']);
            $table->string('sous_categorie',100)->nullable();
            $table->decimal('prix', 10, 2);
            $table->unsignedInteger('stock')->default(0);
            $table->unsignedInteger('stock_minimum')->default(5);
            $table->enum('etat', ['neuf','occasion','reconditionne'])->default('neuf');
            $table->string('origine',50)->nullable();
            $table->text('description')->nullable();
            $table->text('specifications')->nullable();
            $table->text('compatible_avec')->nullable();
            $table->string('image',255)->nullable();
            $table->decimal('poids', 6, 2)->nullable();
            $table->string('dimensions',50)->nullable();
            $table->tinyInteger('garantie_mois')->default(0);
            $table->boolean('disponible')->default(true);
            $table->timestamps();
            $table->index('reference');
            $table->index('marque_compatible');
            $table->index('categorie');
            $table->index('stock');
            $table->index('disponible');
            // Fulltext indexes are supported only on some drivers (MySQL). If unavailable,
            // create a normal composite index as a fallback for basic searching.
            if (Schema::getConnection()->getDriverName() === 'mysql') {
                $table->fullText(['nom', 'description', 'marque_compatible', 'modele_compatible'], 'ft_search_pieces');
            } else {
                $table->index(['nom', 'description', 'marque_compatible', 'modele_compatible'], 'idx_search_pieces');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pieces_detachees');
    }
};

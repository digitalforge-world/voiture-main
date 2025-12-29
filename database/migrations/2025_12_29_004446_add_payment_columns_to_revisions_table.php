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
        Schema::table('revisions', function (Blueprint $table) {
            if (!Schema::hasColumn('revisions', 'montant_paye')) {
                $table->decimal('montant_paye', 10, 2)->default(0)->after('montant_final');
            }

            if (!Schema::hasColumn('revisions', 'statut_paiement')) {
                $table->enum('statut_paiement', ['non_paye', 'partiel', 'paye'])->default('non_paye')->after('montant_paye');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('revisions', function (Blueprint $table) {
            $table->dropColumn(['montant_paye', 'statut_paiement']);
        });
    }
};

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
            // Ajouter diagnostic_technique si n'existe pas
            if (!Schema::hasColumn('revisions', 'diagnostic_technique')) {
                $table->text('diagnostic_technique')->nullable()->after('diagnostic');
            }

            // Ajouter notes_internes si n'existe pas
            if (!Schema::hasColumn('revisions', 'notes_internes')) {
                $table->text('notes_internes')->nullable()->after('notes');
            }

            // Ajouter client_nom si n'existe pas
            if (!Schema::hasColumn('revisions', 'client_nom')) {
                $table->string('client_nom')->nullable()->after('user_id');
            }

            // Ajouter client_email si n'existe pas
            if (!Schema::hasColumn('revisions', 'client_email')) {
                $table->string('client_email')->nullable()->after('client_nom');
            }

            // Ajouter client_telephone si n'existe pas
            if (!Schema::hasColumn('revisions', 'client_telephone')) {
                $table->string('client_telephone')->nullable()->after('client_email');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('revisions', function (Blueprint $table) {
            $table->dropColumn([
                'diagnostic_technique',
                'notes_internes',
                'client_nom',
                'client_email',
                'client_telephone'
            ]);
        });
    }
};

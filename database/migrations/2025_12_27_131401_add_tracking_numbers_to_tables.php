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
        // Ajouter tracking_number aux commandes de voitures
        Schema::table('commandes_voitures', function (Blueprint $table) {
            if (!Schema::hasColumn('commandes_voitures', 'tracking_number')) {
                $table->string('tracking_number', 20)->unique()->after('id');
            }
            if (!Schema::hasColumn('commandes_voitures', 'client_nom')) {
                $table->string('client_nom')->nullable()->after('tracking_number');
                $table->string('client_email')->nullable();
                $table->string('client_telephone')->nullable();
            }
        });

        // Ajouter tracking_number aux locations
        Schema::table('locations', function (Blueprint $table) {
            if (!Schema::hasColumn('locations', 'tracking_number')) {
                $table->string('tracking_number', 20)->unique()->after('id');
            }
            if (!Schema::hasColumn('locations', 'client_nom')) {
                $table->string('client_nom')->nullable()->after('tracking_number');
                $table->string('client_email')->nullable();
                $table->string('client_telephone')->nullable();
            }
        });

        // Ajouter tracking_number aux commandes de pièces
        Schema::table('commandes_pieces', function (Blueprint $table) {
            if (!Schema::hasColumn('commandes_pieces', 'tracking_number')) {
                $table->string('tracking_number', 20)->unique()->after('id');
            }
            if (!Schema::hasColumn('commandes_pieces', 'client_nom')) {
                $table->string('client_nom')->nullable()->after('tracking_number');
                $table->string('client_email')->nullable();
                $table->string('client_telephone')->nullable();
            }
        });

        // Ajouter tracking_number aux révisions
        Schema::table('revisions', function (Blueprint $table) {
            if (!Schema::hasColumn('revisions', 'tracking_number')) {
                $table->string('tracking_number', 20)->unique()->after('id');
            }
            if (!Schema::hasColumn('revisions', 'client_nom')) {
                $table->string('client_nom')->nullable()->after('tracking_number');
                $table->string('client_email')->nullable();
                $table->string('client_telephone')->nullable();
            }
        });

        // Modifier la table users pour admin uniquement
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'is_admin')) {
                $table->boolean('is_admin')->default(false)->after('role');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commandes_voitures', function (Blueprint $table) {
            $table->dropColumn(['tracking_number', 'client_nom', 'client_email', 'client_telephone']);
        });

        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn(['tracking_number', 'client_nom', 'client_email', 'client_telephone']);
        });

        Schema::table('commandes_pieces', function (Blueprint $table) {
            $table->dropColumn(['tracking_number', 'client_nom', 'client_email', 'client_telephone']);
        });

        Schema::table('revisions', function (Blueprint $table) {
            $table->dropColumn(['tracking_number', 'client_nom', 'client_email', 'client_telephone']);
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'is_admin')) {
                $table->dropColumn('is_admin');
            }
        });
    }
};

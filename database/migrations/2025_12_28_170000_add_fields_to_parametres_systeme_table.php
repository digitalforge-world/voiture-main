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
        Schema::table('parametres_systeme', function (Blueprint $table) {
            if (!Schema::hasColumn('parametres_systeme', 'titre')) {
                $table->string('titre')->nullable()->after('cle');
            }
            if (!Schema::hasColumn('parametres_systeme', 'groupe')) {
                $table->string('groupe')->default('general')->after('type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parametres_systeme', function (Blueprint $table) {
            $table->dropColumn(['titre', 'groupe']);
        });
    }
};

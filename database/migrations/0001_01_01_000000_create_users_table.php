<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100);
            $table->string('prenom', 100);
            $table->string('email', 150)->unique();
            $table->string('telephone', 20)->unique();
            $table->string('mot_de_passe', 255);
            $table->enum('role', ['client', 'agent', 'admin'])->default('client');
            $table->text('adresse')->nullable();
            $table->string('ville', 100)->nullable();
            $table->string('pays', 100)->default('Togo');
            $table->string('photo_profil', 255)->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->timestamp('date_creation')->useCurrent();
            $table->timestamp('date_modification')->useCurrent()->useCurrentOnUpdate();
            $table->index('email', 'idx_email');
            $table->index('role', 'idx_role');
            $table->index('actif', 'idx_actif');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
        // Note: other tables (password_reset_tokens, sessions...) are defined elsewhere or not required per db.sql
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};

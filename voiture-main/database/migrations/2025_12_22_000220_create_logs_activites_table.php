<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logs_activites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action',100);
            $table->string('table_concernee',50)->nullable();
            $table->unsignedBigInteger('enregistrement_id')->nullable();
            $table->text('details')->nullable();
            $table->string('ip_address',45)->nullable();
            $table->string('user_agent',255)->nullable();
            $table->timestamp('date_action')->useCurrent();
            $table->index('user_id');
            $table->index('action');
            $table->index('date_action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logs_activites');
    }
};

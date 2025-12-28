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
        // 1. Marketing : Coupons
        Schema::create('marketing_coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique(); // Ex: SUMMER2024
            $table->enum('type', ['percentage', 'fixed'])->default('fixed');
            $table->decimal('value', 10, 2); // Montant ou Pourcentage
            $table->integer('max_uses')->nullable();
            $table->integer('current_uses')->default(0);
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Suppliers and Partners
        Schema::create('partner_suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->enum('type', ['dealer', 'auction', 'logistics', 'service', 'other'])->default('dealer');
            $table->string('contact_person', 100)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('country', 100)->nullable();
            $table->text('address')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 3. Customer Support Tickets
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('subject');
            $table->enum('status', ['open', 'answered', 'customer_reply', 'resolved', 'closed'])->default('open');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->timestamps();
        });

        Schema::create('support_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('support_tickets')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Sender
            $table->text('message');
            $table->boolean('is_internal_note')->default(false); // Admin notes invisible to user
            $table->timestamps();
        });

        // 4. Invoices (Simplified)
        Schema::create('accounting_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number', 50)->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('related_type')->nullable(); // OrderCar, OrderPart, Rental
            $table->unsignedBigInteger('related_id')->nullable();
            $table->decimal('amount_total', 15, 2);
            $table->enum('status', ['draft', 'sent', 'paid', 'cancelled'])->default('draft');
            $table->date('due_date')->nullable();
            $table->date('paid_date')->nullable();
            $table->string('pdf_path')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounting_invoices');
        Schema::dropIfExists('support_messages');
        Schema::dropIfExists('support_tickets');
        Schema::dropIfExists('partner_suppliers');
        Schema::dropIfExists('marketing_coupons');
    }
};

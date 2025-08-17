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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_id')->unique()->comment('Unique transaction ID');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('product_id')->constrained();
            $table->string('customer_phone')->nullable()->comment('Customer phone number');
            $table->string('customer_email')->nullable()->comment('Customer email');
            $table->json('customer_data')->comment('Customer input data (ID, zone, phone, etc.)');
            $table->decimal('amount', 15, 2)->comment('Transaction amount');
            $table->decimal('service_fee', 15, 2)->default(0)->comment('Service fee for guest users');
            $table->decimal('total_amount', 15, 2)->comment('Total amount including fees');
            $table->enum('status', ['pending', 'processing', 'success', 'failed', 'cancelled', 'failed_pending_refund'])->default('pending');
            $table->enum('payment_method', ['balance', 'tripay', 'manual_transfer'])->comment('Payment method used');
            $table->string('payment_reference')->nullable()->comment('Payment reference from gateway');
            $table->string('digiflazz_trx_id')->nullable()->comment('Transaction ID from Digiflazz');
            $table->text('notes')->nullable()->comment('Additional notes or error messages');
            $table->string('refund_token')->nullable()->comment('Token for guest refund claim');
            $table->timestamp('processed_at')->nullable()->comment('When transaction was processed');
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['user_id', 'status']);
            $table->index(['status', 'created_at']);
            $table->index('invoice_id');
            $table->index('refund_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
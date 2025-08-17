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
        Schema::create('topup_requests', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_id')->unique()->comment('Unique top-up ID');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2)->comment('Top-up amount');
            $table->decimal('service_fee', 15, 2)->default(0)->comment('Service fee');
            $table->decimal('total_amount', 15, 2)->comment('Total amount including fees');
            $table->enum('payment_method', ['tripay', 'manual_transfer'])->comment('Payment method');
            $table->string('payment_reference')->nullable()->comment('Payment reference from gateway');
            $table->enum('status', ['pending', 'processing', 'success', 'failed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable()->comment('Additional notes');
            $table->timestamp('processed_at')->nullable()->comment('When top-up was processed');
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['user_id', 'status']);
            $table->index(['status', 'created_at']);
            $table->index('invoice_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topup_requests');
    }
};
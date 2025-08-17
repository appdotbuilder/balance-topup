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
        Schema::create('balance_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['topup', 'purchase', 'refund', 'commission', 'withdrawal'])->comment('Type of balance change');
            $table->decimal('amount', 15, 2)->comment('Amount of change (positive for credit, negative for debit)');
            $table->decimal('balance_before', 15, 2)->comment('Balance before the change');
            $table->decimal('balance_after', 15, 2)->comment('Balance after the change');
            $table->string('reference_id')->nullable()->comment('Reference to related transaction/topup');
            $table->string('description')->comment('Description of the balance change');
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['user_id', 'created_at']);
            $table->index(['type', 'created_at']);
            $table->index('reference_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balance_history');
    }
};
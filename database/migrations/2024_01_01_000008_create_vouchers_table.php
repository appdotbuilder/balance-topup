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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->comment('Voucher code');
            $table->string('name')->comment('Voucher name/title');
            $table->text('description')->nullable()->comment('Voucher description');
            $table->decimal('amount', 15, 2)->comment('Voucher balance amount');
            $table->integer('usage_limit')->default(1)->comment('How many times this voucher can be used');
            $table->integer('used_count')->default(0)->comment('How many times this voucher has been used');
            $table->datetime('valid_from')->comment('Voucher valid from date');
            $table->datetime('valid_until')->comment('Voucher valid until date');
            $table->boolean('is_active')->default(true)->comment('Voucher availability status');
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['code', 'is_active']);
            $table->index(['is_active', 'valid_from', 'valid_until']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->comment('Product code from Digiflazz');
            $table->string('name')->comment('Product name');
            $table->string('category')->comment('Product category (Game, Pulsa, PPOB, etc.)');
            $table->string('brand')->comment('Product brand');
            $table->string('type')->comment('Product type (prepaid, postpaid, etc.)');
            $table->decimal('modal', 15, 2)->comment('Base price from Digiflazz');
            $table->decimal('sell_price', 15, 2)->comment('Price for basic users');
            $table->decimal('reseller_price', 15, 2)->comment('Price for reseller users');
            $table->text('description')->nullable()->comment('Product description');
            $table->json('input_fields')->comment('Required input fields configuration');
            $table->boolean('is_active')->default(true)->comment('Product availability status');
            $table->boolean('check_account_available')->default(false)->comment('Whether account check is available');
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['category', 'is_active']);
            $table->index(['brand', 'is_active']);
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
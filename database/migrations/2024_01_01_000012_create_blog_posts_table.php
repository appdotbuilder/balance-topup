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
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('Post title');
            $table->string('slug')->unique()->comment('URL-friendly post slug');
            $table->text('excerpt')->nullable()->comment('Post excerpt/summary');
            $table->longText('content')->comment('Post content');
            $table->string('featured_image')->nullable()->comment('Featured image URL');
            $table->foreignId('author_id')->constrained('users');
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft')->comment('Post status');
            $table->datetime('published_at')->nullable()->comment('Publication date');
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['status', 'published_at']);
            $table->index(['author_id', 'status']);
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
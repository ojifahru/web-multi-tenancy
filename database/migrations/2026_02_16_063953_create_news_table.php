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
        Schema::create('news', function (Blueprint $table) {
            $table->id();

            $table->foreignId('study_program_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->json('title');
            $table->json('slug');
            $table->json('excerpt')->nullable();
            $table->json('content');

            $table->foreignId('author_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('category_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->dateTime('published_at')->default(now())->nullable();

            $table->enum('status', ['draft', 'published', 'archived'])
                ->default('draft')
                ->index();

            $table->boolean('is_featured')->default(false)->index();

            $table->softDeletes();
            $table->timestamps();

            $table->index(['study_program_id', 'status']);
            $table->unique(['study_program_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};

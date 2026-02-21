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
        Schema::create('lecturers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('study_program_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('nidn');
            $table->string('name');
            $table->string('slug');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->text('biography')->nullable();
            $table->boolean('is_active')->default(true)->index();

            $table->softDeletes();
            $table->timestamps();

            $table->unique(['study_program_id', 'nidn']);
            $table->unique(['study_program_id', 'email']);
            $table->unique(['study_program_id', 'slug']);

            $table->index(['study_program_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecturers');
    }
};

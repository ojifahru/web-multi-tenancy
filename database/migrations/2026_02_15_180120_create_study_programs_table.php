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
        Schema::create('study_programs', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->string('code')->unique();
            $table->string('domain')->unique();
            $table->json('description')->nullable();
            $table->boolean('is_active')->default(true);

            // ======================
            // Academic Metadata
            // ======================
            $table->json('faculty')->nullable();
            $table->json('degree_level', 20)->nullable();
            $table->json('accreditation', 50)->nullable();
            $table->year('established_year')->nullable();

            // ======================
            // Branding & Contact
            // ======================
            $table->string('logo_path')->nullable();
            $table->string('favicon_path')->nullable();
            $table->string('banner_path')->nullable();

            $table->string('email')->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('address')->nullable();

            // ======================
            // Vision & Mission
            // ======================
            $table->json('vision')->nullable();
            $table->json('mission')->nullable();

            // ======================
            // Website / Profile Content
            // ======================
            $table->json('about')->nullable();
            $table->json('objectives')->nullable();

            // ======================
            // Social Media
            // ======================
            $table->string('facebook_link')->nullable();
            $table->string('instagram_link')->nullable();
            $table->string('twitter_link')->nullable();
            $table->string('linkedin_link')->nullable();
            $table->string('youtube_link')->nullable();

            // ======================
            // SEO Metadata
            // ======================
            $table->json('meta_title')->nullable();
            $table->json('meta_description')->nullable();
            $table->json('meta_keywords')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_programs');
    }
};

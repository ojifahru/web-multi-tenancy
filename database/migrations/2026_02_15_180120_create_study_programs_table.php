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
            $table->string('name');
            $table->string('code')->unique();
            $table->string('domain')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);

            // ======================
            // Academic Metadata
            // ======================
            $table->string('faculty')->nullable();
            $table->string('degree_level', 20)->nullable();
            $table->string('accreditation', 50)->nullable();
            $table->string('accreditation_file_path')->nullable();
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
            $table->text('vision')->nullable();
            $table->text('mission')->nullable();

            // ======================
            // Website / Profile Content
            // ======================
            $table->longText('about')->nullable();
            $table->text('objectives')->nullable();

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
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
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

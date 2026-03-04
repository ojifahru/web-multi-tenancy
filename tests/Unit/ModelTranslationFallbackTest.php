<?php

use App\Models\Category;
use App\Models\Lecturer;
use App\Models\StudyProgram;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

test('category resolves localized values and slug with fallback locale', function () {
    $tenant = StudyProgram::withoutEvents(function (): StudyProgram {
        return StudyProgram::query()->create([
            'name' => [
                'id' => 'Program Studi Tes',
                'en' => 'Test Study Program',
            ],
            'code' => 'category-fallback',
            'domain' => 'category-fallback.local',
            'is_active' => true,
        ]);
    });

    $category = Category::query()->create([
        'study_program_id' => $tenant->id,
        'name' => [
            'en' => 'Only English Category',
        ],
        'slug' => [
            'en' => 'only-english-category',
        ],
        'description' => [
            'en' => 'Only english description',
        ],
    ]);

    expect($category->resolveLocalizedValue('name', 'id'))->toBe('Only English Category');
    expect($category->resolveLocalizedValue('description', 'id'))->toBe('Only english description');
    expect($category->resolveSlug('id'))->toBe('only-english-category');
    expect($category->matchesSlug('only-english-category'))->toBeTrue();
    expect($category->matchesSlug('ONLY-ENGLISH-CATEGORY'))->toBeTrue();
    expect($category->matchesSlug('missing-slug'))->toBeFalse();
});

test('tag resolves localized values and slug with fallback locale', function () {
    $tenant = StudyProgram::withoutEvents(function (): StudyProgram {
        return StudyProgram::query()->create([
            'name' => [
                'id' => 'Program Studi Tes',
                'en' => 'Test Study Program',
            ],
            'code' => 'tag-fallback',
            'domain' => 'tag-fallback.local',
            'is_active' => true,
        ]);
    });

    $tag = Tag::query()->create([
        'study_program_id' => $tenant->id,
        'name' => [
            'en' => 'Only English Tag',
        ],
        'slug' => [
            'en' => 'only-english-tag',
        ],
    ]);

    expect($tag->resolveLocalizedValue('name', 'id'))->toBe('Only English Tag');
    expect($tag->resolveSlug('id'))->toBe('only-english-tag');
    expect($tag->matchesSlug('only-english-tag'))->toBeTrue();
    expect($tag->matchesSlug('ONLY-ENGLISH-TAG'))->toBeTrue();
    expect($tag->matchesSlug('missing-slug'))->toBeFalse();
});

test('study program resolves localized values with fallback locale', function () {
    $studyProgram = StudyProgram::withoutEvents(function (): StudyProgram {
        return StudyProgram::query()->create([
            'name' => [
                'en' => 'Only English Program',
            ],
            'code' => 'study-program-fallback',
            'domain' => 'study-program-fallback.local',
            'description' => [
                'en' => 'Only english description',
            ],
            'vision' => [
                'en' => 'Only english vision',
            ],
            'is_active' => true,
        ]);
    });

    expect($studyProgram->resolveLocalizedValue('name', 'id'))->toBe('Only English Program');
    expect($studyProgram->resolveLocalizedValue('description', 'id'))->toBe('Only english description');
    expect($studyProgram->resolveLocalizedValue('vision', 'id'))->toBe('Only english vision');
    expect($studyProgram->resolveSlug('id'))->toBeNull();
});

test('lecturer resolves biography with fallback locale', function () {
    $studyProgram = StudyProgram::withoutEvents(function (): StudyProgram {
        return StudyProgram::query()->create([
            'name' => [
                'id' => 'Program Studi Dosen',
                'en' => 'Lecturer Study Program',
            ],
            'code' => 'lecturer-fallback',
            'domain' => 'lecturer-fallback.local',
            'is_active' => true,
        ]);
    });

    $lecturer = Lecturer::query()->create([
        'study_program_id' => $studyProgram->id,
        'nidn' => '1234567890',
        'name' => 'Dosen Test',
        'slug' => 'dosen-test',
        'email' => 'dosen-test@example.com',
        'biography' => [
            'en' => 'Only english biography',
        ],
        'is_active' => true,
    ]);

    expect($lecturer->resolveLocalizedValue('biography', 'id'))->toBe('Only english biography');
    expect($lecturer->resolveSlug('id'))->toBe('dosen-test');
    expect($lecturer->matchesSlug('dosen-test'))->toBeTrue();
    expect($lecturer->matchesSlug('DOSEN-TEST'))->toBeTrue();
    expect($lecturer->matchesSlug('missing-slug'))->toBeFalse();
});

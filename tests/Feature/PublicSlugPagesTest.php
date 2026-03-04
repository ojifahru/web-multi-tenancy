<?php

use App\Models\Facility;
use App\Models\News;
use App\Models\StudyProgram;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('news detail pages using localized slugs return successful responses', function () {
    $tenant = createActiveStudyProgramForSlugPages('news-slug.local');

    $news = News::query()->create([
        'study_program_id' => $tenant->id,
        'title' => [
            'id' => 'Berita Tes',
            'en' => 'Test News',
        ],
        'slug' => [
            'id' => 'berita-tes',
            'en' => 'test-news',
        ],
        'excerpt' => [
            'id' => 'Ringkasan berita tes',
            'en' => 'Test news excerpt',
        ],
        'content' => [
            'id' => '<p>Konten berita tes</p>',
            'en' => '<p>Test news content</p>',
        ],
        'status' => 'published',
        'published_at' => now()->subMinute(),
    ]);

    $news = $news->fresh();

    expect($news)->not->toBeNull();
    expect($news?->getTranslation('slug', 'id', false))->toBe('berita-tes');
    expect($news?->getTranslation('slug', 'en', false))->toBe('test-news');

    $newsSlugId = $news?->getTranslation('slug', 'id', true);

    $newsResolved = News::query()
        ->where('study_program_id', $tenant->id)
        ->where('status', 'published')
        ->where('published_at', '<=', now())
        ->get()
        ->first(function (News $candidate) use ($newsSlugId): bool {
            return $candidate->getTranslation('slug', 'id', false) === $newsSlugId;
        });

    expect($newsResolved)->not->toBeNull();
    expect((string) $newsResolved?->getRawOriginal('slug'))->toContain('berita-tes');
    expect($newsResolved?->matchesSlug('berita-tes'))->toBeTrue();

    $this->get('http://'.$tenant->domain.'/id/berita')
        ->assertSuccessful()
        ->assertSee('Berita Tes');

    $this
        ->get('http://'.$tenant->domain.'/id/berita/'.$newsSlugId)
        ->assertSuccessful();

    $this
        ->get('http://'.$tenant->domain.'/en/news/'.$news->getTranslation('slug', 'en', true))
        ->assertSuccessful();
});

test('facility detail pages using localized slugs return successful responses', function () {
    $tenant = createActiveStudyProgramForSlugPages('facility-slug.local');

    $facility = Facility::query()->create([
        'study_program_id' => $tenant->id,
        'name' => [
            'id' => 'Laboratorium Komputer',
            'en' => 'Computer Laboratory',
        ],
        'slug' => [
            'id' => 'laboratorium-komputer',
            'en' => 'computer-laboratory',
        ],
        'description' => [
            'id' => 'Deskripsi fasilitas tes',
            'en' => 'Test facility description',
        ],
    ]);

    $facility = $facility->fresh();

    expect($facility)->not->toBeNull();
    expect($facility?->getTranslation('slug', 'id', false))->toBe('laboratorium-komputer');
    expect($facility?->getTranslation('slug', 'en', false))->toBe('computer-laboratory');

    $facilitySlugId = $facility?->getTranslation('slug', 'id', true);

    $facilityResolved = Facility::query()
        ->where('study_program_id', $tenant->id)
        ->get()
        ->first(function (Facility $candidate) use ($facilitySlugId): bool {
            return $candidate->getTranslation('slug', 'id', false) === $facilitySlugId;
        });

    expect($facilityResolved)->not->toBeNull();

    $this->get('http://'.$tenant->domain.'/id/fasilitas')
        ->assertSuccessful()
        ->assertSee('Laboratorium Komputer');

    $this
        ->get('http://'.$tenant->domain.'/id/fasilitas/'.$facilitySlugId)
        ->assertSuccessful();

    $this
        ->get('http://'.$tenant->domain.'/en/facilities/'.$facility->getTranslation('slug', 'en', true))
        ->assertSuccessful();
});

test('news detail does not 404 when current locale translation is missing', function () {
    $tenant = createActiveStudyProgramForSlugPages('news-fallback.local');

    $news = News::query()->create([
        'study_program_id' => $tenant->id,
        'title' => [
            'en' => 'Only English News',
        ],
        'slug' => [
            'en' => 'only-english-news',
        ],
        'excerpt' => [
            'en' => 'Only english excerpt',
        ],
        'content' => [
            'en' => '<p>Only english content</p>',
        ],
        'status' => 'published',
        'published_at' => now()->subMinute(),
    ])->fresh();

    expect($news)->not->toBeNull();
    expect($news?->resolveSlug('id'))->toBe('only-english-news');

    $this->get('http://'.$tenant->domain.'/id/berita')
        ->assertSuccessful()
        ->assertSee('Only English News');

    $this->get('http://'.$tenant->domain.'/id/berita/only-english-news')
        ->assertSuccessful()
        ->assertSee('Only English News');
});

test('facility detail does not 404 when current locale translation is missing', function () {
    $tenant = createActiveStudyProgramForSlugPages('facility-fallback.local');

    $facility = Facility::query()->create([
        'study_program_id' => $tenant->id,
        'name' => [
            'en' => 'Only English Facility',
        ],
        'slug' => [
            'en' => 'only-english-facility',
        ],
        'description' => [
            'en' => 'Only english facility description',
        ],
    ])->fresh();

    expect($facility)->not->toBeNull();
    expect($facility?->resolveSlug('id'))->toBe('only-english-facility');

    $this->get('http://'.$tenant->domain.'/id/fasilitas')
        ->assertSuccessful()
        ->assertSee('Only English Facility');

    $this->get('http://'.$tenant->domain.'/id/fasilitas/only-english-facility')
        ->assertSuccessful()
        ->assertSee('Only English Facility');
});

function createActiveStudyProgramForSlugPages(string $domain = 'localhost'): StudyProgram
{
    return StudyProgram::withoutEvents(function () use ($domain): StudyProgram {
        return StudyProgram::query()->create([
            'name' => [
                'id' => 'Program Studi Tes',
                'en' => 'Test Study Program',
            ],
            'code' => 'test-slug',
            'domain' => $domain,
            'is_active' => true,
        ]);
    });
}

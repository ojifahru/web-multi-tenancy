<?php

namespace Database\Factories;

use App\Models\Facility;
use App\Models\StudyProgram;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Facility>
 */
class FacilityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\App\Models\Facility>
     */
    protected $model = Facility::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $facilityName = fake()->randomElement([
            ['id' => 'Perpustakaan Digital', 'en' => 'Digital Library'],
            ['id' => 'Laboratorium Komputer', 'en' => 'Computer Laboratory'],
            ['id' => 'Kelas Cerdas', 'en' => 'Smart Classroom'],
            ['id' => 'Ruang Diskusi Mahasiswa', 'en' => 'Student Discussion Room'],
            ['id' => 'Studio Multimedia', 'en' => 'Multimedia Studio'],
            ['id' => 'Auditorium Akademik', 'en' => 'Academic Auditorium'],
            ['id' => 'Pusat Riset', 'en' => 'Research Center'],
            ['id' => 'Ruang Kerja Bersama', 'en' => 'Co-Working Space'],
        ]);

        $sequenceNumber = fake()->numberBetween(1, 20);
        $slugSuffix = fake()->unique()->numberBetween(100, 999999);

        $nameId = $facilityName['id'] . ' ' . $sequenceNumber;
        $nameEn = $facilityName['en'] . ' ' . $sequenceNumber;

        return [
            'study_program_id' => fn(): int => $this->resolveStudyProgramId(),
            'name' => [
                'id' => $nameId,
                'en' => $nameEn,
            ],
            'slug' => [
                'id' => Str::slug($nameId) . '-' . $slugSuffix,
                'en' => Str::slug($nameEn) . '-' . $slugSuffix,
            ],
            'description' => fake()->optional(0.85)->boolean() ? $this->descriptionTranslations() : null,
        ];
    }

    public function withDescription(): static
    {
        return $this->state(fn(): array => [
            'description' => $this->descriptionTranslations(),
        ]);
    }

    public function withoutDescription(): static
    {
        return $this->state(fn(): array => [
            'description' => null,
        ]);
    }

    protected function resolveStudyProgramId(): int
    {
        $existingStudyProgramId = StudyProgram::query()->inRandomOrder()->value('id');

        if ($existingStudyProgramId !== null) {
            return (int) $existingStudyProgramId;
        }

        $studyProgram = StudyProgram::query()->create([
            'name' => [
                'id' => 'Program Studi ' . fake()->company(),
                'en' => fake()->company() . ' Study Program',
            ],
            'code' => strtoupper(fake()->unique()->bothify('SP###')),
            'domain' => fake()->unique()->domainName(),
            'description' => [
                'id' => fake()->sentence(),
                'en' => fake()->sentence(),
            ],
            'is_active' => true,
        ]);

        return (int) $studyProgram->id;
    }

    private function descriptionTranslations(): array
    {
        return [
            'id' => fake()->paragraphs(2, true),
            'en' => fake()->paragraphs(2, true),
        ];
    }
}

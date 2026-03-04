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
        $name = fake()->randomElement([
            'Perpustakaan Digital',
            'Laboratorium Komputer',
            'Smart Classroom',
            'Ruang Diskusi Mahasiswa',
            'Studio Multimedia',
            'Auditorium Akademik',
            'Pusat Riset',
            'Co-Working Space',
        ]).' '.fake()->numberBetween(1, 20);

        return [
            'study_program_id' => fn (): int => $this->resolveStudyProgramId(),
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(100, 999999),
            'description' => fake()->optional(0.85)->paragraphs(2, true),
        ];
    }

    public function withDescription(): static
    {
        return $this->state(fn (): array => [
            'description' => fake()->paragraphs(2, true),
        ]);
    }

    public function withoutDescription(): static
    {
        return $this->state(fn (): array => [
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
            'name' => fake()->company().' Study Program',
            'code' => strtoupper(fake()->unique()->bothify('SP###')),
            'domain' => fake()->unique()->domainName(),
            'description' => fake()->sentence(),
            'is_active' => true,
        ]);

        return (int) $studyProgram->id;
    }
}

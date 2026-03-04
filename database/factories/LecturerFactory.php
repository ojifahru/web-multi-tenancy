<?php

namespace Database\Factories;

use App\Models\Lecturer;
use App\Models\StudyProgram;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lecturer>
 */
class LecturerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\App\Models\Lecturer>
     */
    protected $model = Lecturer::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->name();

        return [
            'study_program_id' => fn (): int => $this->resolveStudyProgramId(),
            'nidn' => fake()->unique()->numerify('##########'),
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(100, 999999),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->optional(0.7)->phoneNumber(),
            'biography' => fake()->optional(0.85)->paragraphs(2, true),
            'is_active' => fake()->boolean(85),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (): array => [
            'is_active' => true,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (): array => [
            'is_active' => false,
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

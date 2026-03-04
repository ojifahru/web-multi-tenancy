<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\News;
use App\Models\StudyProgram;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\News>
 */
class NewsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\App\Models\News>
     */
    protected $model = News::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(6);
        $status = fake()->randomElement(['draft', 'published', 'archived']);

        $publishedAt = match ($status) {
            'published' => fake()->dateTimeBetween('-6 months', 'now'),
            'archived' => fake()->dateTimeBetween('-2 years', '-6 months'),
            default => null,
        };

        return [
            'study_program_id' => fn (): int => $this->resolveStudyProgramId(),
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(1000, 999999),
            'excerpt' => fake()->optional(0.9)->paragraph(),
            'content' => collect(fake()->paragraphs(6))
                ->map(fn (string $paragraph): string => '<p>'.e($paragraph).'</p>')
                ->implode(PHP_EOL),
            'author_id' => fn (): ?int => $this->resolveAuthorId(),
            'category_id' => fn (array $attributes): ?int => $this->resolveCategoryId((int) $attributes['study_program_id']),
            'published_at' => $publishedAt,
            'status' => $status,
            'is_featured' => fake()->boolean(20),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (News $news): void {
            if (! fake()->boolean(35)) {
                return;
            }

            $tagIds = Tag::query()
                ->where('study_program_id', $news->study_program_id)
                ->inRandomOrder()
                ->limit(fake()->numberBetween(1, 3))
                ->pluck('id');

            if ($tagIds->isNotEmpty()) {
                $news->tags()->syncWithoutDetaching($tagIds->all());
            }
        });
    }

    public function draft(): static
    {
        return $this->state(fn (): array => [
            'status' => 'draft',
            'published_at' => null,
            'is_featured' => false,
        ]);
    }

    public function published(): static
    {
        return $this->state(fn (): array => [
            'status' => 'published',
            'published_at' => fake()->dateTimeBetween('-6 months', 'now'),
        ]);
    }

    public function archived(): static
    {
        return $this->state(fn (): array => [
            'status' => 'archived',
            'published_at' => fake()->dateTimeBetween('-2 years', '-6 months'),
            'is_featured' => false,
        ]);
    }

    public function featured(): static
    {
        return $this->published()->state(fn (): array => [
            'is_featured' => true,
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

    protected function resolveAuthorId(): ?int
    {
        if (! fake()->boolean(75)) {
            return null;
        }

        $existingUserId = User::query()->inRandomOrder()->value('id');

        if ($existingUserId !== null) {
            return (int) $existingUserId;
        }

        return (int) User::factory()->create()->id;
    }

    protected function resolveCategoryId(int $studyProgramId): ?int
    {
        if (! fake()->boolean(60)) {
            return null;
        }

        $categoryId = Category::query()
            ->where('study_program_id', $studyProgramId)
            ->inRandomOrder()
            ->value('id');

        return $categoryId !== null ? (int) $categoryId : null;
    }
}

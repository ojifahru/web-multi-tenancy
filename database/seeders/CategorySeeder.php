<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\StudyProgram;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(?int $studyProgramId = null): void
    {
        $studyProgramIds = $this->resolveStudyProgramIds($studyProgramId);

        if ($studyProgramIds->isEmpty()) {
            if (isset($this->command)) {
                $this->command->warn('Tidak ada program studi. Buat prodi terlebih dahulu sebelum menjalankan CategorySeeder.');
            }

            return;
        }

        foreach ($studyProgramIds as $programId) {
            $this->seedDefaultCategories((int) $programId);
        }
    }

    private function seedDefaultCategories(int $studyProgramId): void
    {
        $defaultCategories = [
            [
                'name' => [
                    'id' => 'Berita',
                    'en' => 'News',
                ],
                'slug' => [
                    'id' => 'berita',
                    'en' => 'news',
                ],
                'description' => [
                    'id' => 'Kategori untuk konten berita terbaru.',
                    'en' => 'Category for the latest news content.',
                ],
            ],
            [
                'name' => [
                    'id' => 'Pengumuman',
                    'en' => 'Announcements',
                ],
                'slug' => [
                    'id' => 'pengumuman',
                    'en' => 'announcements',
                ],
                'description' => [
                    'id' => 'Kategori untuk informasi dan pengumuman resmi.',
                    'en' => 'Category for official information and announcements.',
                ],
            ],
        ];

        $existingCategories = Category::query()
            ->where('study_program_id', $studyProgramId)
            ->get();

        foreach ($defaultCategories as $categoryData) {
            $categoryExists = $existingCategories->contains(
                fn(Category $category): bool => $category->getTranslation('slug', 'id') === $categoryData['slug']['id']
            );

            if ($categoryExists) {
                continue;
            }

            Category::query()->create([
                'study_program_id' => $studyProgramId,
                'name' => $categoryData['name'],
                'slug' => $categoryData['slug'],
                'description' => $categoryData['description'],
            ]);
        }
    }

    private function resolveStudyProgramIds(?int $studyProgramId = null): Collection
    {
        $studyPrograms = StudyProgram::query()
            ->orderBy('code')
            ->get(['id', 'code']);

        if ($studyPrograms->isEmpty()) {
            return collect();
        }

        if ($studyProgramId !== null) {
            return $studyPrograms
                ->where('id', $studyProgramId)
                ->pluck('id')
                ->values();
        }

        if (! isset($this->command)) {
            return $studyPrograms->pluck('id');
        }

        $this->command->table(
            ['ID', 'Kode Prodi'],
            $studyPrograms
                ->map(static fn(StudyProgram $studyProgram): array => [
                    $studyProgram->id,
                    $studyProgram->code ?: '-',
                ])
                ->all()
        );

        $selectedStudyProgramId = $this->command->ask('Masukkan ID prodi untuk CategorySeeder (kosongkan untuk semua prodi)');

        if ($selectedStudyProgramId === null || $selectedStudyProgramId === '') {
            return $studyPrograms->pluck('id');
        }

        if (! ctype_digit((string) $selectedStudyProgramId)) {
            throw new InvalidArgumentException('ID prodi harus berupa angka.');
        }

        $selectedStudyProgramId = (int) $selectedStudyProgramId;

        if (! $studyPrograms->contains('id', $selectedStudyProgramId)) {
            throw new InvalidArgumentException('ID prodi tidak ditemukan.');
        }

        return collect([$selectedStudyProgramId]);
    }
}

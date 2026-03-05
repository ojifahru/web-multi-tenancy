<?php

namespace Database\Seeders;

use App\Models\StudyProgram;
use Illuminate\Database\Seeder;
use InvalidArgumentException;

class AcademicContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! StudyProgram::query()->exists()) {
            if (isset($this->command)) {
                $this->command->warn('Tidak ada program studi. Buat prodi terlebih dahulu sebelum menjalankan AcademicContentSeeder.');
            }

            return;
        }

        $selectedStudyProgramId = $this->resolveStudyProgramIdForSeeding();

        $this->callWith(CategorySeeder::class, [
            'studyProgramId' => $selectedStudyProgramId,
        ]);

        $this->callWith(LecturerSeeder::class, [
            'studyProgramId' => $selectedStudyProgramId,
        ]);

        $this->callWith(FacilitySeeder::class, [
            'studyProgramId' => $selectedStudyProgramId,
        ]);

        $this->callWith(NewsSeeder::class, [
            'studyProgramId' => $selectedStudyProgramId,
        ]);
    }

    private function resolveStudyProgramIdForSeeding(): ?int
    {
        if (! isset($this->command)) {
            return null;
        }

        $studyPrograms = StudyProgram::query()
            ->orderBy('code')
            ->get(['id', 'code']);

        $this->command->table(
            ['ID', 'Kode Prodi'],
            $studyPrograms
                ->map(static fn(StudyProgram $studyProgram): array => [
                    $studyProgram->id,
                    $studyProgram->code ?: '-',
                ])
                ->all()
        );

        $selectedStudyProgramId = $this->command->ask('Masukkan ID prodi untuk AcademicContentSeeder (kosongkan untuk semua prodi)');

        if ($selectedStudyProgramId === null || $selectedStudyProgramId === '') {
            return null;
        }

        if (! ctype_digit((string) $selectedStudyProgramId)) {
            throw new InvalidArgumentException('ID prodi harus berupa angka.');
        }

        $selectedStudyProgramId = (int) $selectedStudyProgramId;

        if (! $studyPrograms->contains('id', $selectedStudyProgramId)) {
            throw new InvalidArgumentException('ID prodi tidak ditemukan.');
        }

        return $selectedStudyProgramId;
    }
}

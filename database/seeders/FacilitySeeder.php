<?php

namespace Database\Seeders;

use App\Models\Facility;
use App\Models\StudyProgram;
use Illuminate\Database\Seeder;
use InvalidArgumentException;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(?int $studyProgramId = null): void
    {
        $studyProgramIds = $this->resolveStudyProgramIds($studyProgramId);

        if ($studyProgramIds->isEmpty()) {
            if (isset($this->command)) {
                $this->command->warn('Tidak ada program studi. Buat prodi terlebih dahulu sebelum menjalankan FacilitySeeder.');
            }

            return;
        }

        foreach ($studyProgramIds as $programId) {
            Facility::factory()
                ->withDescription()
                ->count(10)
                ->state([
                    'study_program_id' => $programId,
                ])
                ->create();

            Facility::factory()
                ->withoutDescription()
                ->count(2)
                ->state([
                    'study_program_id' => $programId,
                ])
                ->create();
        }
    }

    private function resolveStudyProgramIds(?int $studyProgramId = null)
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
                ->map(static fn (StudyProgram $studyProgram): array => [
                    $studyProgram->id,
                    $studyProgram->code ?: '-',
                ])
                ->all()
        );

        $selectedStudyProgramId = $this->command->ask('Masukkan ID prodi untuk FacilitySeeder (kosongkan untuk semua prodi)');

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

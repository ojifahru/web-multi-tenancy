<?php

namespace Database\Seeders;

use App\Models\Lecturer;
use App\Models\StudyProgram;
use Illuminate\Database\Seeder;

class LecturerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studyProgramIds = StudyProgram::query()->pluck('id');

        if ($studyProgramIds->isEmpty()) {
            Lecturer::factory()->active()->count(12)->create();
            Lecturer::factory()->inactive()->count(3)->create();

            return;
        }

        foreach ($studyProgramIds as $studyProgramId) {
            Lecturer::factory()
                ->active()
                ->count(12)
                ->state([
                    'study_program_id' => $studyProgramId,
                ])
                ->create();

            Lecturer::factory()
                ->inactive()
                ->count(3)
                ->state([
                    'study_program_id' => $studyProgramId,
                ])
                ->create();
        }
    }
}

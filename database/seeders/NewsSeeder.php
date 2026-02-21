<?php

namespace Database\Seeders;

use App\Models\News;
use App\Models\StudyProgram;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studyProgramIds = StudyProgram::query()->pluck('id');

        if ($studyProgramIds->isEmpty()) {
            News::factory()->count(12)->create();

            return;
        }

        foreach ($studyProgramIds as $studyProgramId) {
            News::factory()
                ->published()
                ->count(8)
                ->state([
                    'study_program_id' => $studyProgramId,
                ])
                ->create();

            News::factory()
                ->featured()
                ->count(2)
                ->state([
                    'study_program_id' => $studyProgramId,
                ])
                ->create();

            News::factory()
                ->draft()
                ->count(3)
                ->state([
                    'study_program_id' => $studyProgramId,
                ])
                ->create();

            News::factory()
                ->archived()
                ->count(2)
                ->state([
                    'study_program_id' => $studyProgramId,
                ])
                ->create();
        }
    }
}

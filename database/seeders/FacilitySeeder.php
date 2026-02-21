<?php

namespace Database\Seeders;

use App\Models\Facility;
use App\Models\StudyProgram;
use Illuminate\Database\Seeder;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studyProgramIds = StudyProgram::query()->pluck('id');

        if ($studyProgramIds->isEmpty()) {
            Facility::factory()->withDescription()->count(10)->create();
            Facility::factory()->withoutDescription()->count(2)->create();

            return;
        }

        foreach ($studyProgramIds as $studyProgramId) {
            Facility::factory()
                ->withDescription()
                ->count(10)
                ->state([
                    'study_program_id' => $studyProgramId,
                ])
                ->create();

            Facility::factory()
                ->withoutDescription()
                ->count(2)
                ->state([
                    'study_program_id' => $studyProgramId,
                ])
                ->create();
        }
    }
}

<?php

use App\Models\StudyProgram;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('the application returns a successful response', function () {
    StudyProgram::withoutEvents(function (): void {
        StudyProgram::query()->create([
            'name' => 'Test Program',
            'code' => 'test',
            'domain' => 'localhost',
            'is_active' => true,
        ]);
    });

    $response = $this
        ->withServerVariables(['HTTP_HOST' => 'localhost'])
        ->get('/');

    $response->assertOk();
});

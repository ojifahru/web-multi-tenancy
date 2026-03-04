<?php

use App\Models\StudyProgram;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('the application redirects root path to default locale', function () {
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

    $response->assertRedirect('/id');
});

<?php

use App\Models\User;
use App\Models\StudyProgram;
use Filament\Notifications\DatabaseNotification as FilamentDatabaseNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('public visitor can submit contact message', function () {
    $tenant = createActiveStudyProgram();

    $response = $this
        ->withServerVariables(['HTTP_HOST' => $tenant->domain])
        ->withHeader('User-Agent', 'Pest Test Agent')
        ->post('/kontak-kami', [
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'phone' => '081234567890',
            'subject' => 'Pertanyaan beasiswa',
            'message' => 'Mohon informasi jadwal pendaftaran beasiswa untuk semester depan.',
        ]);

    $response
        ->assertRedirect('/kontak-kami')
        ->assertSessionHas('success');

    $this->assertDatabaseHas('contact_messages', [
        'study_program_id' => $tenant->id,
        'name' => 'Budi Santoso',
        'email' => 'budi@example.com',
        'phone' => '081234567890',
        'subject' => 'Pertanyaan beasiswa',
        'message' => 'Mohon informasi jadwal pendaftaran beasiswa untuk semester depan.',
        'is_read' => false,
        'ip_address' => '127.0.0.1',
        'user_agent' => 'Pest Test Agent',
    ]);
});

test('contact message submission validates required fields', function () {
    $tenant = createActiveStudyProgram();

    $response = $this
        ->withServerVariables(['HTTP_HOST' => $tenant->domain])
        ->from('/kontak-kami')
        ->post('/kontak-kami', [
            'name' => '',
            'email' => 'email-tidak-valid',
            'phone' => '',
            'subject' => '',
            'message' => 'pendek',
        ]);

    $response
        ->assertRedirect('/kontak-kami')
        ->assertSessionHasErrors(['name', 'email', 'subject', 'message']);

    $this->assertDatabaseCount('contact_messages', 0);
});

test('study program admins receive notification when contact message is submitted', function () {
    $tenant = createActiveStudyProgram();
    $adminUser = User::factory()->create();

    $tenant->users()->attach($adminUser);

    $this
        ->withServerVariables(['HTTP_HOST' => $tenant->domain])
        ->post('/kontak-kami', [
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@example.com',
            'phone' => '089900112233',
            'subject' => 'Informasi jadwal kuliah',
            'message' => 'Mohon info jadwal perkuliahan semester ini dan cara akses LMS.',
        ])
        ->assertRedirect('/kontak-kami');

    $this->assertDatabaseHas('notifications', [
        'notifiable_type' => User::class,
        'notifiable_id' => $adminUser->id,
        'type' => FilamentDatabaseNotification::class,
    ]);
});

function createActiveStudyProgram(string $domain = 'localhost'): StudyProgram
{
    return StudyProgram::withoutEvents(function () use ($domain): StudyProgram {
        return StudyProgram::query()->create([
            'name' => 'Program Studi Test',
            'code' => 'test',
            'domain' => $domain,
            'is_active' => true,
        ]);
    });
}

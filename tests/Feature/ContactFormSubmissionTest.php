<?php

use App\Models\StudyProgram;
use App\Models\User;
use Filament\Notifications\DatabaseNotification as FilamentDatabaseNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('public visitor can submit contact message', function () {
    $tenant = createActiveStudyProgram();
    $contactPath = '/id/kontak-kami';

    $response = $this
        ->withServerVariables(['HTTP_HOST' => $tenant->domain])
        ->withHeader('User-Agent', 'Pest Test Agent')
        ->post($contactPath, [
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'phone' => '081234567890',
            'subject' => 'Pertanyaan beasiswa',
            'message' => 'Mohon informasi jadwal pendaftaran beasiswa untuk semester depan.',
        ]);

    $response
        ->assertRedirect($contactPath)
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
    $contactPath = '/id/kontak-kami';

    $response = $this
        ->withServerVariables(['HTTP_HOST' => $tenant->domain])
        ->from($contactPath)
        ->post($contactPath, [
            'name' => '',
            'email' => 'email-tidak-valid',
            'phone' => '',
            'subject' => '',
            'message' => 'pendek',
        ]);

    $response
        ->assertRedirect($contactPath)
        ->assertSessionHasErrors(['name', 'email', 'subject', 'message']);

    $this->assertDatabaseCount('contact_messages', 0);
});

test('study program admins receive notification when contact message is submitted', function () {
    $tenant = createActiveStudyProgram();
    $adminUser = User::factory()->create();
    $contactPath = '/id/kontak-kami';

    $tenant->users()->attach($adminUser);

    $this
        ->withServerVariables(['HTTP_HOST' => $tenant->domain])
        ->post($contactPath, [
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@example.com',
            'phone' => '089900112233',
            'subject' => 'Informasi jadwal kuliah',
            'message' => 'Mohon info jadwal perkuliahan semester ini dan cara akses LMS.',
        ])
        ->assertRedirect($contactPath);

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

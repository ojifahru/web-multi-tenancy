<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        // buat role super_admin kalau belum ada
        $role = Role::firstOrCreate(['name' => 'super_admin']);

        // buat user super admin
        $user = User::firstOrCreate(
            ['email' => 'superadmin@mail.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt(env('SUPERADMIN_PASSWORD', 'default123')),
            ]
        );

        $user->assignRole($role);
    }
}

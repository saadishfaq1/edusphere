<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndUserSeeder extends Seeder
{
    public function run(): void
    {
        $schools = School::all();

        foreach ($schools as $school) {
            app(PermissionRegistrar::class)->setPermissionsTeamId($school->id);

            $adminRole = Role::firstOrCreate([
                'name' => 'School Admin',
                'guard_name' => 'web',
            ]);

            Role::firstOrCreate(['name' => 'Teacher', 'guard_name' => 'web']);
            Role::firstOrCreate(['name' => 'Student', 'guard_name' => 'web']);
            Role::firstOrCreate(['name' => 'Parent', 'guard_name' => 'web']);
            Role::firstOrCreate(['name' => 'Accountant', 'guard_name' => 'web']);
            Role::firstOrCreate(['name' => 'Librarian', 'guard_name' => 'web']);

            $admin = User::firstOrCreate(
                ['email' => 'admin@'.$school->subdomain.'.test'],
                [
                    'name' => $school->name.' Admin',
                    'password' => Hash::make('password'),
                    'school_id' => $school->id,
                    'email_verified_at' => now(),
                ]
            );

            $admin->assignRole($adminRole);
        }
    }
}
<?php

namespace Database\Seeders;

use App\Models\School;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    public function run(): void
    {
        School::create([
            'name' => 'Greenwood High School',
            'subdomain' => 'greenwood',
            'email' => 'admin@greenwood.test',
            'phone' => '0300-0000000',
            'plan' => 'trial',
            'status' => 'active',
            'trial_ends_at' => now()->addDays(14),
        ]);

        School::create([
            'name' => 'Oakwood Academy',
            'subdomain' => 'oakwood',
            'email' => 'admin@oakwood.test',
            'phone' => '0300-1111111',
            'plan' => 'trial',
            'status' => 'active',
            'trial_ends_at' => now()->addDays(14),
        ]);
    }
}
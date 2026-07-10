<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@kmc.go.tz',
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'HR Officer',
            'email' => 'hr@kmc.go.tz',
            'role' => 'hr',
        ]);

        $this->call([
            DepartmentSeeder::class,
            TrainingCategorySeeder::class,
            TrainingInstitutionSeeder::class,
            FundingSourceSeeder::class,
        ]);
    }
}

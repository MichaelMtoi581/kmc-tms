<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            'Administration and Human Resources',
            'Finance and Trade',
            'Planning and Coordination',
            'Primary Education',
            'Secondary Education',
            'Health',
            'Agriculture and Fisheries',
            'Community Development',
            'ICT',
            'Legal Services',
            'Works',
            'Environment',
        ];

        foreach ($departments as $name) {
            Department::updateOrCreate(['name' => $name]);
        }
    }
}

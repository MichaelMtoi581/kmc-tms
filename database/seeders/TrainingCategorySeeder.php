<?php

namespace Database\Seeders;

use App\Models\TrainingCategory;
use Illuminate\Database\Seeder;

class TrainingCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Short Course',
            'Workshop',
            'Seminar',
            'Conference',
            'Diploma',
            'Advanced Diploma',
            'Bachelor Degree',
            'Masters Degree',
            'PhD',
            'Professional Certification',
            'Study Tour',
        ];

        foreach ($categories as $name) {
            TrainingCategory::updateOrCreate(['name' => $name]);
        }
    }
}

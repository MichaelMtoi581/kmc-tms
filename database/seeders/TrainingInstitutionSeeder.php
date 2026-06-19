<?php

namespace Database\Seeders;

use App\Models\TrainingInstitution;
use Illuminate\Database\Seeder;

class TrainingInstitutionSeeder extends Seeder
{
    public function run(): void
    {
        $institutions = [
            ['name' => 'Local Government Training Institute (LGTI)', 'location' => 'Dodoma'],
            ['name' => 'Tanzania Public Service College (TPSC)', 'location' => 'Dar es Salaam'],
            ['name' => 'Institute of Finance Management (IFM)', 'location' => 'Dar es Salaam'],
            ['name' => 'Mzumbe University', 'location' => 'Morogoro'],
            ['name' => 'University of Dar es Salaam (UDSM)', 'location' => 'Dar es Salaam'],
            ['name' => 'Ardhi University', 'location' => 'Dar es Salaam'],
            ['name' => 'Muhimbili University of Health and Allied Sciences (MUHAS)', 'location' => 'Dar es Salaam'],
            ['name' => 'National Institute of Transport (NIT)', 'location' => 'Dar es Salaam'],
            ['name' => 'Dar es Salaam Institute of Technology (DIT)', 'location' => 'Dar es Salaam'],
            ['name' => 'Eastern Africa Statistical Training Centre (EASTC)', 'location' => 'Dar es Salaam'],
        ];

        foreach ($institutions as $data) {
            TrainingInstitution::updateOrCreate(
                ['name' => $data['name']],
                $data
            );
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\FundingSource;
use Illuminate\Database\Seeder;

class FundingSourceSeeder extends Seeder
{
    public function run(): void
    {
        $sources = [
            ['name' => 'Kinondoni Municipal Council', 'description' => 'Funds allocated from the municipal council budget'],
            ['name' => 'Central Government', 'description' => 'Funds from the national government through ministerial allocations'],
            ['name' => 'Donor', 'description' => 'Funds from international development partners and donors'],
            ['name' => 'Personal Sponsorship', 'description' => 'Self-sponsored training by the staff member'],
            ['name' => 'Development Partner', 'description' => 'Funds from development cooperation agencies'],
            ['name' => 'Other', 'description' => 'Other funding sources not classified above'],
        ];

        foreach ($sources as $data) {
            FundingSource::updateOrCreate(
                ['name' => $data['name']],
                $data
            );
        }
    }
}

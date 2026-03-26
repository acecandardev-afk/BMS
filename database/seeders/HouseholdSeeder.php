<?php

namespace Database\Seeders;

use App\Models\Household;
use Illuminate\Database\Seeder;

class HouseholdSeeder extends Seeder
{
    public function run(): void
    {
        $households = [
            [
                'household_number' => 'HH-2026-0001',
                'zone' => 'Zone 1',
                'address' => 'Purok 1, Barangay Cantupa',
                'remarks' => 'Seed household record',
            ],
            [
                'household_number' => 'HH-2026-0002',
                'zone' => 'Zone 2',
                'address' => 'Purok 2, Barangay Cantupa',
                'remarks' => 'Seed household record',
            ],
            [
                'household_number' => 'HH-2026-0003',
                'zone' => 'Zone 3',
                'address' => 'Purok 3, Barangay Cantupa',
                'remarks' => 'Seed household record',
            ],
        ];

        foreach ($households as $row) {
            Household::updateOrCreate(
                ['household_number' => $row['household_number']],
                $row
            );
        }
    }
}

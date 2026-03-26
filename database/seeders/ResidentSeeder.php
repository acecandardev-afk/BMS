<?php

namespace Database\Seeders;

use App\Models\Household;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Database\Seeder;

class ResidentSeeder extends Seeder
{
    public function run(): void
    {
        $residentUser = User::where('email', 'resident@cantupa.gov.ph')->first();
        $household = Household::where('household_number', 'HH-2026-0001')->first();

        $residents = [
            [
                'user_id' => $residentUser?->id,
                'household_id' => $household?->id,
                'first_name' => 'Juan',
                'middle_name' => 'Santos',
                'last_name' => 'Dela Cruz',
                'suffix' => null,
                'birthdate' => '1995-04-12',
                'birthplace' => 'La Libertad, Negros Oriental',
                'gender' => 'male',
                'civil_status' => 'single',
                'nationality' => 'Filipino',
                'religion' => 'Roman Catholic',
                'occupation' => 'Farmer',
                'zone' => 'Zone 1',
                'address' => 'Purok 1, Barangay Cantupa',
                'contact_number' => '09171234567',
                'email' => 'resident@cantupa.gov.ph',
                'voter_status' => true,
                'is_indigenous' => false,
                'is_pwd' => false,
                'is_solo_parent' => false,
                'is_4ps' => false,
                'remarks' => 'Seed resident linked to resident user.',
            ],
            [
                'user_id' => null,
                'household_id' => Household::where('household_number', 'HH-2026-0002')->value('id'),
                'first_name' => 'Maria',
                'middle_name' => 'Lopez',
                'last_name' => 'Santos',
                'suffix' => null,
                'birthdate' => '1988-11-03',
                'birthplace' => 'Dumaguete City',
                'gender' => 'female',
                'civil_status' => 'married',
                'nationality' => 'Filipino',
                'religion' => 'Roman Catholic',
                'occupation' => 'Vendor',
                'zone' => 'Zone 2',
                'address' => 'Purok 2, Barangay Cantupa',
                'contact_number' => '09179876543',
                'email' => 'maria.santos@example.com',
                'voter_status' => true,
                'is_indigenous' => false,
                'is_pwd' => false,
                'is_solo_parent' => false,
                'is_4ps' => true,
                'remarks' => 'Seed resident sample.',
            ],
        ];

        foreach ($residents as $row) {
            Resident::updateOrCreate(
                ['first_name' => $row['first_name'], 'last_name' => $row['last_name'], 'birthdate' => $row['birthdate']],
                $row
            );
        }

        // Assign household head to Juan if present.
        if ($household) {
            $headId = Resident::where('first_name', 'Juan')->where('last_name', 'Dela Cruz')->value('id');
            if ($headId) {
                $household->update(['head_resident_id' => $headId]);
            }
        }
    }
}

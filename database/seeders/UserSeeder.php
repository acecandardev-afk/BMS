<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name'      => 'System Administrator',
                'email'     => 'admin@cantupa.gov.ph',
                'password'  => Hash::make('Admin@1234'),
                'role'      => User::ROLE_ADMIN,
                'is_active' => true,
            ],
            [
                'name'      => 'Staff Encoder',
                'email'     => 'staff@cantupa.gov.ph',
                'password'  => Hash::make('Staff@1234'),
                'role'      => User::ROLE_STAFF,
                'is_active' => true,
            ],
            [
                'name'      => 'Barangay Captain',
                'email'     => 'captain@cantupa.gov.ph',
                'password'  => Hash::make('Captain@1234'),
                'role'      => User::ROLE_SIGNATORY,
                'is_active' => true,
            ],
            [
                'name'      => 'Juan Dela Cruz',
                'email'     => 'resident@cantupa.gov.ph',
                'password'  => Hash::make('Resident@1234'),
                'role'      => User::ROLE_RESIDENT,
                'is_active' => true,
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(['email' => $user['email']], $user);
        }
    }
}
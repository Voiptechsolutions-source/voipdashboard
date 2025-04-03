<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'voip@gmail.com'], // Keep the same email to update existing admin
            [
                'username' => 'VoIP_SuperAdmin', // Stronger username
                'password' => Hash::make('V0ip@Secure#2025'), // Stronger password
                'role' => 'superadmin',
                'is_active' => true,
                'is_delete' => false,
            ]
        );
    }
}

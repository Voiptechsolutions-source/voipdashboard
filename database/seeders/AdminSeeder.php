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
            ['email' => 'voip@gmail.com'],
            [
                'username' => 'voipadmin',
                'password' => Hash::make('voip123'),
                'role' => 'superadmin',
                'is_active' => true,
                'is_delete' => false,
            ]
        );
    }
}

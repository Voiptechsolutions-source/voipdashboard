<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        // List of roles to seed
        $roles = [
            ['name' => 'sales'],
            ['name' => 'support'],
            ['name' => 'user'],
            
            // Add more roles as needed
        ];

        foreach ($roles as $roleData) {
            Role::firstOrCreate(
                ['name' => $roleData['name']],
                $roleData
            );
        }

        $this->command->info('Roles seeded successfully! Permissions must be assigned by Superadmin.');
    }
}
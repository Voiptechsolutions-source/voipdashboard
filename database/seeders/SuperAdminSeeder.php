<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $superAdminRole = Role::firstOrCreate(['name' => 'superadmin']);
        $pages = ['dashboard', 'leads', 'import-customers', 'customers', 'support', 'Roles', 'users'];
        $permissionIds = [];
        foreach ($pages as $page) {
            $permission = Permission::firstOrCreate(['page_name' => $page]);
            $permissionIds[$permission->id] = ['can_view' => 1, 'can_edit' => 1, 'can_delete' => 1];
        }
        $superAdminRole->permissions()->sync($permissionIds);

        User::updateOrCreate(
            ['email' => 'voip@gmail.com'],
            [
                'username' => 'VoIP_SuperAdmin',
                'password' => Hash::make('V0ip@Secure#2025'),
                'role_id' => $superAdminRole->id,
                'is_active' => true,
                'is_delete' => false,
            ]
        );

        $this->command->info('Superadmin seeded successfully!');
    }
}
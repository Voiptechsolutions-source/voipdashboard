<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'page_name' => 'Dashboard',
                'can_view' => true,
                'can_edit' => false,
                'can_delete' => false,
            ],
            [
                'page_name' => 'Leads',
                'can_view' => true,
                'can_edit' => true,
                'can_delete' => false,
            ],
            [
                'page_name' => 'Import-customers',
                'can_view' => true,
                'can_edit' => false,
                'can_delete' => false,
            ],
            [
                'page_name' => 'Customers',
                'can_view' => true,
                'can_edit' => false,
                'can_delete' => false,
            ],
            [
                'page_name' => 'Support',
                'can_view' => true,
                'can_edit' => false,
                'can_delete' => false,
            ],
            [
                'page_name' => 'Roles',
                'can_view' => true,
                'can_edit' => false,
                'can_delete' => false,
            ],
            [
                'page_name' => 'Users',
                'can_view' => true,
                'can_edit' => false,
                'can_delete' => false,
            ],
        ];

        foreach ($permissions as $permission) {
            // Check if permission already exists by page_name
            if (!Permission::where('page_name', $permission['page_name'])->exists()) {
                Permission::create($permission);
            }
        }
    }
}
<?php

// database/migrations/2025_04_04_modify_users_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the existing role column if it exists
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
            
            // Add role_id if it doesn't exist, position after password
            if (!Schema::hasColumn('users', 'role_id')) {
                $table->foreignId('role_id')
                      ->nullable()
                      ->after('password')  // Changed from after('id') to after('password')
                      ->constrained('roles')
                      ->onDelete('set null');
                
                // Add index
                $table->index('role_id', 'users_role_id_index');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Reverse the changes
            if (Schema::hasColumn('users', 'role_id')) {
                $table->dropForeign(['role_id']);
                $table->dropIndex('users_role_id_index');
                $table->dropColumn('role_id');
            }
            
            // Add back the role column if it was there
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->nullable()->after('password');
            }
        });
    }
}
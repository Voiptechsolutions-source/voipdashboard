<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // ✅ Check if the `customers` table exists before renaming
        if (Schema::hasTable('customers') && !Schema::hasTable('leads')) {
            Schema::rename('customers', 'leads');
        }

        // ✅ Update foreign key references (if applicable)
        Schema::table('supports', function (Blueprint $table) {
            // Check if foreign key exists before dropping
            $foreignKeys = DB::select("SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE 
                                       WHERE TABLE_NAME = 'supports' AND COLUMN_NAME = 'lead_id' 
                                       AND CONSTRAINT_SCHEMA = DATABASE()");

            if (!empty($foreignKeys)) {
                $table->dropForeign(['lead_id']);
            }

            // Re-add foreign key pointing to `leads` instead of `customers`
            $table->foreign('lead_id')
                  ->references('id')
                  ->on('leads')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ✅ Rollback: Rename `leads` back to `customers`
        if (Schema::hasTable('leads') && !Schema::hasTable('customers')) {
            Schema::rename('leads', 'customers');
        }

        // ✅ Restore foreign key reference (if necessary)
        Schema::table('supports', function (Blueprint $table) {
            // Drop the updated foreign key
            $foreignKeys = DB::select("SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE 
                                       WHERE TABLE_NAME = 'supports' AND COLUMN_NAME = 'lead_id' 
                                       AND CONSTRAINT_SCHEMA = DATABASE()");

            if (!empty($foreignKeys)) {
                $table->dropForeign(['lead_id']);
            }

            // Re-add foreign key pointing to `customers` again
            $table->foreign('lead_id')
                  ->references('id')
                  ->on('customers')
                  ->onDelete('cascade');
        });
    }
};

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
        // ✅ Rename 'customers' table to 'leads' if necessary
        if (Schema::hasTable('customers') && !Schema::hasTable('leads')) {
            Schema::rename('customers', 'leads');
        }

        // ✅ Ensure `supports` table exists before modifying
        if (Schema::hasTable('supports')) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // ✅ Check if the foreign key exists before dropping it
            $foreignKeys = DB::select("SELECT CONSTRAINT_NAME 
                FROM information_schema.TABLE_CONSTRAINTS 
                WHERE TABLE_NAME = 'supports' 
                AND CONSTRAINT_TYPE = 'FOREIGN KEY'");

            foreach ($foreignKeys as $fk) {
                if ($fk->CONSTRAINT_NAME == 'supports_lead_id_foreign') {
                    Schema::table('supports', function (Blueprint $table) {
                        $table->dropForeign('supports_lead_id_foreign');
                    });
                }
            }

            // ✅ Ensure `lead_id` column exists and update its type
            if (Schema::hasColumn('supports', 'lead_id')) {
                Schema::table('supports', function (Blueprint $table) {
                    $table->unsignedBigInteger('lead_id')->change();

                    // ✅ Re-add foreign key pointing to `leads`
                    $table->foreign('lead_id')
                        ->references('id')
                        ->on('leads')
                        ->onDelete('cascade');
                });
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ✅ Ensure `supports` table exists before modifying
        if (Schema::hasTable('supports')) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // ✅ Check if the foreign key exists before dropping it
            $foreignKeys = DB::select("SELECT CONSTRAINT_NAME 
                FROM information_schema.TABLE_CONSTRAINTS 
                WHERE TABLE_NAME = 'supports' 
                AND CONSTRAINT_TYPE = 'FOREIGN KEY'");

            foreach ($foreignKeys as $fk) {
                if ($fk->CONSTRAINT_NAME == 'supports_lead_id_foreign') {
                    Schema::table('supports', function (Blueprint $table) {
                        $table->dropForeign('supports_lead_id_foreign');
                    });
                }
            }

            // ✅ Ensure `lead_id` column exists and update its type
            if (Schema::hasColumn('supports', 'lead_id')) {
                Schema::table('supports', function (Blueprint $table) {
                    $table->unsignedBigInteger('lead_id')->change();

                    // ✅ Re-add foreign key pointing back to `customers`
                    $table->foreign('lead_id')
                        ->references('id')
                        ->on('customers')
                        ->onDelete('cascade');
                });
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        // ✅ Rename `leads` back to `customers`
        if (Schema::hasTable('leads') && !Schema::hasTable('customers')) {
            Schema::rename('leads', 'customers');
        }
    }
};

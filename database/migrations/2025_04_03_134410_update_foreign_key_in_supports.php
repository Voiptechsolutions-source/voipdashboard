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
        DB::beginTransaction();
        try {
            // ✅ Rename 'customers' table to 'leads' if necessary
            if (Schema::hasTable('customers') && !Schema::hasTable('leads')) {
                Schema::rename('customers', 'leads');
            }

            // ✅ Ensure `supports` table exists before modifying
            if (Schema::hasTable('supports')) {
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');

                // ✅ Check if the foreign key exists before dropping it
                $foreignKeys = DB::select("SELECT CONSTRAINT_NAME 
                    FROM information_schema.KEY_COLUMN_USAGE 
                    WHERE TABLE_NAME = 'supports' 
                    AND CONSTRAINT_NAME = 'supports_lead_id_foreign'");

                if (!empty($foreignKeys)) {
                    Schema::table('supports', function (Blueprint $table) {
                        $table->dropForeign(['lead_id']);
                    });
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

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::beginTransaction();
        try {
            if (Schema::hasTable('supports')) {
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');

                // ✅ Check if the foreign key exists before dropping it
                $foreignKeys = DB::select("SELECT CONSTRAINT_NAME 
                    FROM information_schema.KEY_COLUMN_USAGE 
                    WHERE TABLE_NAME = 'supports' 
                    AND CONSTRAINT_NAME = 'supports_lead_id_foreign'");

                if (!empty($foreignKeys)) {
                    Schema::table('supports', function (Blueprint $table) {
                        $table->dropForeign(['lead_id']);
                    });
                }

                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
};

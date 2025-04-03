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
            // ✅ Rename table if required
            if (Schema::hasTable('customers') && !Schema::hasTable('leads')) {
                Schema::rename('customers', 'leads');
            }

            // ✅ Ensure `leads.id` is `unsignedBigInteger`
            Schema::table('leads', function (Blueprint $table) {
                $table->unsignedBigInteger('id')->change();
            });

            // ✅ Ensure `supports` table exists before modifying
            if (Schema::hasTable('supports')) {
                Schema::table('supports', function (Blueprint $table) {
                    // ✅ Drop foreign key if it exists
                    $table->dropForeign(['lead_id']);

                    // ✅ Ensure `lead_id` matches `leads.id`
                    $table->unsignedBigInteger('lead_id')->change();

                    // ✅ Re-add foreign key
                    $table->foreign('lead_id')
                        ->references('id')
                        ->on('leads')
                        ->onDelete('cascade');
                });
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
            // ✅ Ensure `supports` table exists before modifying
            if (Schema::hasTable('supports')) {
                Schema::table('supports', function (Blueprint $table) {
                    // ✅ Drop foreign key before rollback
                    $table->dropForeign(['lead_id']);

                    // ✅ Restore `lead_id` data type (assuming previous type was `unsignedBigInteger`)
                    $table->bigInteger('lead_id')->change();

                    // ✅ Re-add foreign key pointing back to `customers`
                    $table->foreign('lead_id')
                        ->references('id')
                        ->on('customers')
                        ->onDelete('cascade');
                });
            }

            // ✅ Rename `leads` back to `customers`
            if (Schema::hasTable('leads') && !Schema::hasTable('customers')) {
                Schema::rename('leads', 'customers');
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
};

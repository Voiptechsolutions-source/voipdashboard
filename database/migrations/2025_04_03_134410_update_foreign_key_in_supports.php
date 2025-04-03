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

            // ✅ Ensure `supports` table exists before modifying
            if (Schema::hasTable('supports')) {
                Schema::table('supports', function (Blueprint $table) {
                    // ✅ Drop foreign key if it exists
                    if ($this->foreignKeyExists('supports', 'lead_id')) {
                        $table->dropForeign(['lead_id']);
                    }

                    // ✅ Ensure `lead_id` has the same type as `leads.id`
                    $table->unsignedBigInteger('lead_id')->change();

                    // ✅ Re-add foreign key pointing to `leads`
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
                    if ($this->foreignKeyExists('supports', 'lead_id')) {
                        $table->dropForeign(['lead_id']);
                    }

                    // ✅ Restore `lead_id` data type (assuming previous type was `unsignedBigInteger`)
                    $table->unsignedBigInteger('lead_id')->change();

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

    /**
     * Helper function to check if a foreign key exists on a table.
     */
    private function foreignKeyExists(string $table, string $column): bool
    {
        return DB::select("
            SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_NAME = ? AND COLUMN_NAME = ? AND CONSTRAINT_SCHEMA = DATABASE()
        ", [$table, $column]) !== [];
    }
};

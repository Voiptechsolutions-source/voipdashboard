<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('convert_leads', function (Blueprint $table) {
            if (!Schema::hasColumn('convert_leads', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
            if (!Schema::hasColumn('convert_leads', 'is_delete')) {
                $table->boolean('is_delete')->default(false);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('convert_leads', function (Blueprint $table) {
            if (Schema::hasColumn('convert_leads', 'is_active')) {
                $table->dropColumn('is_active');
            }
            if (Schema::hasColumn('convert_leads', 'is_delete')) {
                $table->dropColumn('is_delete');
            }
        });
    }
};

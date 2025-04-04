<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('convert_leads', function (Blueprint $table) {
            $table->unsignedBigInteger('lead_id')->change(); // Ensure it's unsigned
            $table->foreign('lead_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('convert_leads', function (Blueprint $table) {
            $table->dropForeign(['lead_id']);
        });
    }
};

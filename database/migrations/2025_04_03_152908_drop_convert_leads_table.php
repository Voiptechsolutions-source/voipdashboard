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
            Schema::dropIfExists('convert_leads');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('convert_leads', function (Blueprint $table) {
            $table->id();
            // Add columns if needed to restore the table
            $table->timestamps();
        });
    }
};

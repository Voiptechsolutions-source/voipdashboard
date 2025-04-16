<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUniqueIndexFromEmailInLeads extends Migration
{
    public function up()
    {
        Schema::table('leads', function (Blueprint $table) {
            // Drop the unique index on email
            $table->dropUnique('leads_email_unique'); // Matches the index name from the previous migration
        });
    }

    public function down()
    {
        Schema::table('leads', function (Blueprint $table) {
            // Re-add the unique constraint if needed for rollback
            $table->unique('email', 'leads_email_unique');
        });
    }
}
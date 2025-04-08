<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueIndexToEmailInLeadsTable extends Migration
{
    public function up()
    {
        Schema::table('leads', function (Blueprint $table) {
            // Add unique index on email
            $table->unique('email', 'leads_email_unique'); // Named index for clarity
        });
    }

    public function down()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropUnique('leads_email_unique');
        });
    }
}
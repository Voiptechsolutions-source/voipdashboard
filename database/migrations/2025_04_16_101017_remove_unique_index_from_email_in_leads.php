<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RemoveUniqueIndexFromEmailInLeads extends Migration
{
    public function up()
    {
        Schema::table('leads', function (Blueprint $table) {
            // Check if the index exists before dropping
            if (DB::getSchemaBuilder()->hasIndex('leads', 'leads_email_unique')) {
                $table->dropUnique('leads_email_unique');
            }
        });
    }

    public function down()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->unique('email', 'leads_email_unique');
        });
    }
}
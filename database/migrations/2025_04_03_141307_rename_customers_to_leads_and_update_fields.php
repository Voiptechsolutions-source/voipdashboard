<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // ✅ Check if 'customers' table exists and rename it to 'leads'
        if (Schema::hasTable('customers') && !Schema::hasTable('leads')) {
            Schema::rename('customers', 'leads');
        }

        // ✅ Ensure the 'leads' table exists (for fresh setups)
        if (!Schema::hasTable('leads')) {
            Schema::create('leads', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('lead_id')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('assigned_to')->nullable();
                $table->string('full_name', 500)->nullable();
                $table->string('email', 100)->nullable();
                $table->string('country_code', 10)->nullable();
                $table->string('contact_no', 20)->nullable();
                $table->text('address')->nullable();
                $table->string('pincode', 100)->nullable();
                $table->text('source')->nullable();
                $table->string('service_name', 500)->nullable();
                $table->string('service_type', 255)->nullable();
                $table->string('industry', 255)->nullable();
                $table->string('number_of_users', 500)->nullable();
                $table->tinyInteger('status')->default(2);
                $table->integer('convertedlead')->nullable();
                $table->text('message')->nullable();
                $table->text('comment')->nullable();
                $table->text('description')->nullable();
                $table->text('customer_description')->nullable();
                $table->json('raw_data')->nullable();
                $table->tinyInteger('is_active')->default(1);
                $table->tinyInteger('is_delete')->default(0);
                $table->timestamps();

                // ✅ Add indexes and foreign keys
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
                $table->index(['lead_id', 'email', 'contact_no', 'status', 'user_id', 'assigned_to', 'source']);
            });
        }
    }

    public function down()
    {
        // ✅ Rollback: If 'leads' table exists, rename it back to 'customers'
        if (Schema::hasTable('leads') && !Schema::hasTable('customers')) {
            Schema::rename('leads', 'customers');
        }
    }
};

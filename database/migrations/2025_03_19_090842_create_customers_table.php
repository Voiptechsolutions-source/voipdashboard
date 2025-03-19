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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 500)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('country_code', 10)->nullable();
            $table->string('contact_no', 20)->nullable();
            $table->text('address')->nullable();
            $table->string('pincode', 100)->nullable();
            $table->boolean('is_active')->default(1);
            $table->boolean('is_delete')->default(0);
            $table->string('service_name', 500)->nullable();
            $table->string('number_of_users', 500)->nullable();
            $table->text('message')->nullable();
            $table->text('comment')->nullable();
            $table->text('description')->nullable();
            $table->text('customer_description')->nullable();
            $table->string('lead_id', 255)->nullable();
            $table->bigInteger('campaign_id')->nullable();
            $table->bigInteger('form_id')->nullable();
            $table->enum('source', ['Google', 'Facebook', 'CSV'])->nullable();
            $table->string('status', 20)->default('0');
            $table->integer('convertedlead')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};

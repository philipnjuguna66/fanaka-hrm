<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_contacts', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('employee_id');
            $table->string('official_email')->unique();
            $table->string('personal_email')->unique();
            $table->string('country');
            $table->string('address');
            $table->string('office_phone_number')->nullable();
            $table->string('office_phone_extension')->nullable();
            $table->string('personal_phone_number');
            $table->string('city');
            $table->string('county');
            $table->string('postal_code');
            $table->json('next_of_kin');
            $table->json('social_links');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hr_contacts');
    }
};

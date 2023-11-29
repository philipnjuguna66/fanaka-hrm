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
        Schema::create('salary_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('employee_id');
            $table->integer('basic_salary');
            $table->string('terms_of_employment')->default(\App\Enums\EmploymentTerms::CONTRACT);
            $table->integer('disability_exemption_amount')->nullable();
            $table->string('exemption_certificate_no')->nullable();
            $table->boolean('has_disability')->default(false);
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
        Schema::dropIfExists('salary_details');
    }
};

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
        Schema::create('hr_details', function (Blueprint $table) {
            $table->id();
            $table->string('staff_number')->nullable();
            $table->date('date_of_employment')->nullable();
            $table->date('contract_start')->nullable();
            $table->date('contract_end')->nullable();
            $table->boolean('board_director')->default(false);
            $table->unsignedInteger('employee_id');
            $table->unsignedInteger('region_id')->nullable();
            $table->unsignedInteger('job_grade_id')->nullable();
            $table->unsignedInteger('job_title_id')->nullable();
            $table->unsignedInteger('department_id')->nullable();
            $table->unsignedInteger('reports_to_job_title_id')->nullable();
            $table->unsignedInteger('business_unit_id')->nullable();
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
        Schema::dropIfExists('hr_details');
    }
};

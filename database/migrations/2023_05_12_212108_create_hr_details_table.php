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
            $table->date('date_of_employment');
            $table->date('contract_start');
            $table->date('contract_end');
            $table->boolean('board_director')->default(false);
            $table->unsignedInteger('employee_id');
            $table->unsignedInteger('region_id');
            $table->unsignedInteger('job_grade_id');
            $table->unsignedInteger('job_title_id');
            $table->unsignedInteger('department_id');
            $table->unsignedInteger('reports_to_job_title_id');
            $table->unsignedInteger('business_unit_id');
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

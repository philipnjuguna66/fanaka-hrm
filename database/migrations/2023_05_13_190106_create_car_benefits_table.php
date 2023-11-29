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
        Schema::create('car_benefits', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('employee_id');
            $table->string('car_reg_no');
            $table->string('make');
            $table->string('body_type');
            $table->string('cc_rating');
            $table->string('type_of_car_cost');
            $table->integer('cost_of_owned_car')->default(0);
            $table->integer('cost_of_hiring')->default(0);
            $table->integer('commissioner_rate')->default(0);
            $table->integer('benefit_rate')->default(0);
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
        Schema::dropIfExists('car_benefits');
    }
};

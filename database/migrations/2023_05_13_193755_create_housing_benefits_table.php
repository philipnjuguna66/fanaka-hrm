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
        Schema::create('housing_benefits', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('employee_id');
            $table->foreignIdFor(\App\Models\HousingBenefitType::class);
            $table->integer('rate')->default(0);
            $table->integer('actual_rent_value')->default(0);
            $table->integer('fair_market_rent_value');
            $table->integer('rent_recovered')->default(0);
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
        Schema::dropIfExists('housing_benefits');
    }
};

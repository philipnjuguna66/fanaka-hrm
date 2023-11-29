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
        Schema::create('benefits', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->boolean('taxable')->default(true);
            $table->string('code')->unique();
            $table->boolean('non_cash')->default(false);
            $table->string('mode');
            $table->integer('taxed_from_amount')->nullable()->default(0);
            $table->string('type');
            $table->integer('fixed_amount')->nullable();
            $table->string('percentage_of')->nullable();
            $table->integer('percentage_value')->nullable();
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
        Schema::dropIfExists('benefits');
    }
};

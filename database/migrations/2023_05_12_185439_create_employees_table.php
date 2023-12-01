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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('gender');
            $table->date('date_of_birth');
            $table->string('residential_status')->nullable();
            $table->string('legal_document_type')->default('nat');
            $table->string('legal_document_number');
            $table->string('kra_pin_no')->nullable();
            $table->string('nssf_no')->nullable();
            $table->string('nhif_no')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('nationality')->nullable();
            $table->string('passport_photo')->nullable();
            $table->json('config')->nullable();
            $table->boolean('should_pay_payee')->default(false);
            $table->boolean('should_pay_nhif')->default(true);
            $table->boolean('should_pay_nssf')->default(true);
            $table->string('status')->default(\App\Enums\EmployeeStatusEnum::ACTIVE);
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
        Schema::dropIfExists('employees');
    }
};

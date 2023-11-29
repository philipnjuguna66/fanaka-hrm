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
        Schema::create('payroll_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Payroll::class);
            $table->foreignIdFor(\App\Models\Employee::class)->constrained();
            $table->decimal('basic_pay',13,2);
            $table->decimal('gross_pay',13,2);
            $table->decimal('tax_allowable_deductions',13,2);
            $table->decimal('car_benefits',13,2);
            $table->decimal('housing_benefits',13,2);
            $table->decimal('taxable_income',10,2);
            $table->decimal('nhif',13,2);
            $table->decimal('nssf',13,2);
            $table->decimal('house_levy',13,2);
            $table->decimal('personal_relief',13,2);
            $table->decimal('insurance_relief',13,2);
            $table->decimal('net_payee',13,2);
            $table->decimal('paye',13,2)->nullable();
            $table->decimal('net_pay',13,2);
            $table->json('deductions')->nullable();
            $table->json('benefits')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_lines');
    }
};

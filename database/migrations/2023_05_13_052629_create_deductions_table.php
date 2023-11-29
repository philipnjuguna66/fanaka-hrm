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
        Schema::create('deductions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\DeductionType::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->string('name');
            $table->string('type');
            $table->decimal('fixed_amount',13)->default(0.00);
            $table->string('percentage_of')->nullable();
            $table->decimal('percentage_value',13)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deductions');
    }
};

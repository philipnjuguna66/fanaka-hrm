<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HousingBenefit extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'housing_benefit_type_id',
        'type',
        'actual_rent_value',
        'fair_market_rent_value',
        'rent_recovered',
        'rate',
    ];


    public function housingBenefitType(): BelongsTo
    {
        return $this->belongsTo(HousingBenefitType::class);
    }
}

<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Employee extends Model implements HasMedia
{

    use HasFactory;
    use InteractsWithMedia;


    /**
     * @throws InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Manipulations::FIT_CROP, 300, 300)
            ->nonQueued();
    }


    protected $dates = [
        'date_of_birth'
    ];

    protected $casts = [
        'config' => 'array',
        'should_pay_payee' => 'boolean'
    ];

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'gender',
        'marital_status',
        'date_of_birth',
        'residential_status',
        'legal_document_type',
        'legal_document_number',
        'kra_pin_no',
        'nssf_no',
        'nhif_no',
        'passport_photo',
        'nationality',
        'should_pay_payee',
    ];

    public function salaryDetail(): \Illuminate\Database\Eloquent\Relations\HasOne
    {

        return $this->hasOne(SalaryDetail::class);

    }

    public function hrDetail(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(HrDetail::class);
    }


    public function hrContact(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(HrContact::class);
    }


    public function employeeBenefits(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Benefit::class)->withPivot('amount');
    }

    public function employeeDeductions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Deduction::class)->withPivot('amount');
    }

    public function carBenefits(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CarBenefit::class);
    }

    public function housingBenefits(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(HousingBenefit::class);
    }

    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class);
    }

    public function payslips(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(PaySlip::class,PayrollLine::class);
    }


    public function name() : Attribute
    {
        return new Attribute(
            get: fn() => $this->first_name .' '. $this->middle_name . ' '. $this->last_name
        );
    }
}

<?php

namespace App\Models;

use App\Casts\StaffNumber;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class HrDetail extends Model
{
    use HasFactory;

    protected $dates = [
        'contract_start',
        'contract_end',
        'date_of_employment'
    ];

    protected $fillable = [
       'staff_number',
       'date_of_employment',
       'contract_start',
       'contract_end',
       'job_grade_id',
       'job_title_id',
       'department_id',
       'reports_to_job_title_id',
       'region_id',
       'business_unit_id',
       'board_director',
       'employee_id'
    ];


    protected $casts = [
        'staff_number' => StaffNumber::class
    ];


    public function jobTitle(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(JobTitle::class);
    }

    public function jobGrade(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(JobGrade::class);
    }

    public function department(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function region(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function businessUnit(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BusinessUnit::class);
    }

    public static function boot(): void
    {
        parent::boot();

        self::creating(function ($model) {
            $model->staff_number = DB::table('hr_details')->max('staff_number') + 1;
        });
    }
}

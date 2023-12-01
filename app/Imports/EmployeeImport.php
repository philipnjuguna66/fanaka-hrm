<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\JobTitle;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use PhpOffice\PhpSpreadsheet\Shared\Date;

class EmployeeImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $collection->each(function ($data){


                if (! Employee::query()->where('legal_document_number', $data['id_no'])->exists()){

                    if (isset($data['full_name']))
                    {
                        $full_name = explode('_', str($data['full_name'])->slug('_')->value() );

                        $data['first_name'] = $full_name[0] ?? null;
                        $data['middle_name'] = $full_name[1] ?? null;
                        $data['last_name'] = $full_name[2] ?? null;
                    }

                    tap(Employee::query()->where([ 'legal_document_number' => $data['id_no']])->firstOrCreate([
                        'first_name' => $data['first_name'],
                        'middle_name' => $data['middle_name'],
                        'last_name' => $data['last_name'],
                        'gender' => $data['gender'],
                        'date_of_birth' => isset($data['date_of_birth']) ? Carbon::parse(Date::excelToDateTimeObject($data['date_of_birth'])) : null,
                        'legal_document_type' => 'nat',
                        'legal_document_number' => $data['id_no'],
                        'kra_pin_no' => $data['kra_pin_no'] ?? null,
                        'nssf_no' => $data['nssf_no'] ?? 0,
                        'nhif_no' => $data['nhif_no'] ?? 0,
                    ]), function (Employee $employee) use ($data){
                        $employee->salaryDetail()->create([
                            'basic_salary' => $data['basic_salary'] ?? 0
                        ]);

                        $employee->hrDetail()->create([
                            'staff_number' => $data['staff_no'] ?? $employee->id,
                            'job_title_id' => JobTitle::query()->where('name', "like", "%{$data['job_title']}%")
                                ->firstOrCreate([
                                    'label' => $data['job_title']
                                ]),

                            'date_of_employment' => isset($data['date_joining']) ? Carbon::parse(Date::excelToDateTimeObject($data['date_joining'])) : null,
                            'contract_start' => Carbon::parse(Date::excelToDateTimeObject($data['date_joining'])),
                        ]);

                        $employee->hrContact()->create([
                            'office_phone_number' => $data['phone_number'] ?? 0
                        ]);


                    });
                }

        });
    }

}

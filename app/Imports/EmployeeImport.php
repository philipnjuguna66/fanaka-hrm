<?php

namespace App\Imports;

use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class EmployeeImport implements ToCollection, WithHeadingRow, WithChunkReading, ShouldQueue
{
    use Importable;
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

               tap(Employee::create([
                   'first_name' => $data['first_name'],
                   'middle_name' => $data['middle_name'],
                   'last_name' => $data['last_name'],
                   'gender' => $data['gender'],
                   'date_of_birth' => Carbon::parse(Date::dateTimeToExcel($data['date_of_birth'])),
                   'legal_document_type' => 'nat',
                   'legal_document_number' => $data['id_no'],
                   'kra_pin_no' => $data['kra_pin_no'],
                   'nssf_no' => $data['nssf_no'] ?? 0,
                   'nhif_no' => $data['nhif_no'] ?? 0,
               ]), function (Employee $employee) use ($data){
                   $employee->salaryDetail()->create([
                       'basic_salary' => $data['basic_salary'] ?? 0
                   ]);
               });
           }
        });
    }

    public function chunkSize(): int
    {
        return  50;
    }
}

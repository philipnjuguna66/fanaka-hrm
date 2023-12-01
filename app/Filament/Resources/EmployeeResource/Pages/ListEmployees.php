<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use App\Imports\EmployeeImport;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ListEmployees extends ListRecords
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            $this->importEmployees(),
        ];
    }

    private function importEmployees() : Actions\Action
    {
        return  Actions\Action::make('import Employees')
            ->form([
                FileUpload::make('employee_file')
                    ->acceptedFileTypes(['text/csv', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                    ->required()
            ])
            ->slideOver()
            ->closeModalByClickingAway()
            ->modalHeading("Upload excel with employee data")
            ->modalContent(fn()  => new HtmlString("<a href='". asset('templates/employee_template.xlsx')."'>Download Template</a>") )
            ->action(function (array $data){
                try {


                    Excel::import(import: new EmployeeImport(), filePath: ($data['employee_file']), readerType: \Maatwebsite\Excel\Excel::XLSX);

                    return Notification::make('error')
                        ->success()
                        ->title("Uploading")
                        ->send();
                }
                catch (\Exception $exception)
                {
                    dump($exception->getMessage());

                    return Notification::make('error')
                        ->danger()
                        ->title("Something went wrong")
                        ->body($exception->getMessage())
                        ->send();
                }
            });

    }
}

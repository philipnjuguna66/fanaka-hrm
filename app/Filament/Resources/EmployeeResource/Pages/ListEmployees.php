<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use App\Imports\EmployeeImport;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\HtmlString;
use Maatwebsite\Excel\Facades\Excel;

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
                ->required()
            ])
            ->slideOver()
            ->closeModalByClickingAway()
            ->modalHeading("Upload excel with employee data")
            ->modalContent(fn()  => new HtmlString("<a href='". asset('templates/employee_template.xlsx')."'>Download Template</a>") )
            ->action(function (array $data){
                try {

                    Excel::import(new EmployeeImport, ($data['employee_file']));

                    return Notification::make('error')
                        ->success()
                        ->title("Uploading")
                        ->send();
                }
                catch (\Exception $exception)
                {
                    return Notification::make('error')
                        ->danger()
                        ->title("Something went wrong")
                        ->body($exception->getMessage())
                        ->send();
                }
            });

    }
}

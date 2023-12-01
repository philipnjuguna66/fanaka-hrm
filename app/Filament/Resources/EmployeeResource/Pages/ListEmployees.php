<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use App\Imports\EmployeeImport;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ListEmployees extends ListRecords
{
    protected static string $resource = EmployeeResource::class;

    public $employee_file;
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
                    ->disk('public')
                    ->directory('employee_file')
                    ->visibility('public')
                    ->reactive()
                ->required()
            ])
            ->slideOver()
            ->closeModalByClickingAway()
            ->action(function (array $data){
                try {


                    $path = $data['employee_file'];

                    Excel::import(new EmployeeImport, $path);


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

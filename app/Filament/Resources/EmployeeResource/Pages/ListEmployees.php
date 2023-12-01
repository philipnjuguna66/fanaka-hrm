<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use App\Imports\EmployeeImport;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
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
                    ->reactive()
                ->required()
            ])
            ->slideOver()
            ->closeModalByClickingAway()
            ->action(function (array $data){
                try {

                    $file = storage_path($data['employee_file']);


                    $spreadsheet = IOFactory::load($file);

                    $worksheet =  $spreadsheet->getFirstSheetIndex();

                    dd($worksheet->getCellCollection());


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

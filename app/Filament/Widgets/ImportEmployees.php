<?php

namespace App\Filament\Widgets;

use App\Imports\EmployeeImport;

use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Widgets\Widget;
use Maatwebsite\Excel\Facades\Excel;

class ImportEmployees extends Widget implements HasForms
{
    use InteractsWithForms;

    public $employee_file;


    protected function getFormSchema(): array
    {
        return [

            Grid::make(3)
                ->schema([

                    Section::make('Import Employee Import')
                        ->schema([
                            FileUpload::make('employee_file')
                                ->required()
                            //->acceptedFileTypes(['csv','xlsx'])
                        ]),
                ]),

        ];
    }


    public function importEmployees()
    {
        try {

            $data = $this->form->getState();


            Excel::import(new EmployeeImport(), ($data['employee_file']));


            return Notification::make('success')
                ->success()
                ->body("successfully uploaded title payments")
                ->sendToDatabase(User::query()->first());
        } catch (\Exception $e) {

            return Notification::make('error')
                ->danger()
                ->body($e->getMessage())
                ->send();
        }


    }


    protected static string $view = 'filament.widgets.import-widget';
}

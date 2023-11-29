<?php

namespace App\Livewire;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\Calculation\Calculation;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ExcelImport extends Component implements HasForms
{
    use InteractsWithForms;


    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }


    public static function form(Form $form):Form
    {
         return  $form->schema([

             FileUpload::make('excel')
         ])->statePath('data');
    }

    public function create(): void
    {
        $file = storage_path('test.xlsx');


        $formulaes = $this->listFormulas($file,'Payroll');


    }

    function listFormulas($file_path, $sheet_name) {
        $formulas = [];
        $spreadsheet = IOFactory::load($file_path);
        $worksheet = $spreadsheet->getSheetByName($sheet_name);

        dd($worksheet->getCellCollection());

        // Enable calculating formulas
        Calculation::getInstance($spreadsheet)->disableCalculationCache();



        foreach ($worksheet->getCellCollection() as $cell) {


            $formula = $cell->getValue();

            $cellInfo = [
                'Cell' => $cell->getCoordinate(),
                'Formula' => $formula,
            ];

            dd($cellInfo);

            $formulas[] = $cellInfo;

//            if (str_starts_with($formula, '=')) {
//                $cellInfo = [
//                    'Cell' => $cell->getCoordinate(),
//                    'Formula' => $formula,
//                ];
//                $formulas[] = $cellInfo;
//            }
        }

        return $formulas;
    }


    public function render()
    {
        return view('livewire.excel-import');
    }
}

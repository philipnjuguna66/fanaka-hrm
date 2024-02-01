<?php

namespace App\Filament\Pages\Employee;

use App\Models\EmployeeDeduction;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class EmployeeDeductionPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $view = "filament.pages.employee.deduction-page";
    public function table(Table $table): Table
    {
        return  $table->query(EmployeeDeduction::query()->with('employee','deduction'))
            ->columns([
                TextColumn::make('employee.name'),
                TextColumn::make('deduction.name'),
                TextColumn::make('amount')->numeric(),
            ]);
    }


}

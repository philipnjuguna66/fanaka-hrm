<?php

namespace App\Filament\Pages\Employee;

use App\Models\EmployeeDeduction;
use Filament\Actions\EditAction;
use Filament\Pages\Page;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class EmployeeDeductionPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $view = "filament.pages.employee.deduction-page";


    protected static ?string $navigationGroup = "HR";


    public function table(Table $table): Table
    {
        return  $table->query(EmployeeDeduction::query())
            ->columns([
                TextColumn::make('employee.name'),
                TextColumn::make('deduction.name'),
                TextColumn::make('amount')->numeric(),
            ])
            ->actions([
                DetachAction::make(),

            ])
            ->emptyState(fn() => new HtmlString("No Deduction"));
    }


}

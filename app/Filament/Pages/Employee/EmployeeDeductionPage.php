<?php

namespace App\Filament\Pages\Employee;

use App\Models\Deduction;
use App\Models\EmployeeDeduction;
use Filament\Actions\EditAction;
use Filament\Pages\Page;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class EmployeeDeductionPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $view = "filament.pages.employee.deduction-page";


    protected static ?string $navigationGroup = "HR";

    protected static ?string $navigationLabel = "Employee Deductions";


    protected static ?string $navigationIcon = 'heroicon-o-users';

    public function table(Table $table): Table
    {
        return  $table->query(EmployeeDeduction::query()->where('deduction_id', '!=', 4))
            ->columns([
                TextColumn::make('employee.name')->searchable(),
                TextColumn::make('deduction.name'),
                TextColumn::make('amount')->numeric(),
            ])
            ->actions([
                DetachAction::make(),

            ])
            ->filters([
                SelectFilter::make('Deduction')
                    ->relationship('deduction', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('Employee')
                    ->relationship('employee', 'first_name')
                    ->searchable()
                    ->preload(),
            ])
            ->emptyState(fn() => new HtmlString("No Deduction"));
    }


}

<?php

namespace App\Filament\Pages\Employee;

use App\Models\Deduction;
use App\Models\EmployeeBenefit;
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

class EmployeeBenefitPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $view = "filament.pages.employee.deduction-page";


    protected static ?string $navigationGroup = "HR";

    protected static ?string $navigationLabel = "Employee Benefits";


    protected static ?string $navigationIcon = 'heroicon-o-users';

    public function table(Table $table): Table
    {
        return  $table->query(EmployeeBenefit::query())
            ->columns([
                TextColumn::make('employee.name'),
                TextColumn::make('benefit.name'),
                TextColumn::make('amount')->numeric(),
            ])
            ->filters([
                SelectFilter::make('deduction_id')
                    ->label('Deduction')
                    ->options(Deduction::all()->pluck('name','id'))
                    ->searchable()
                    ->preload(),
                SelectFilter::make('employee_id')
                    ->label('Employee')
                    ->options(EmployeeDeduction::all()->pluck('first_name','id'))
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                DetachAction::make(),

            ])
            ->emptyState(fn() => new HtmlString("No Benefits"));
    }


}

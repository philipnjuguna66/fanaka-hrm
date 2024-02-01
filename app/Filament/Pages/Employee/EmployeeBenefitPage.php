<?php

namespace App\Filament\Pages\Employee;

use App\Models\Deduction;
use App\Models\EmployeeBenefit;
use App\Models\EmployeeDeduction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
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


    protected static ?int $navigationSort = 10;


    public function table(Table $table): Table
    {
        return  $table->query(EmployeeBenefit::query())
            ->columns([
                TextColumn::make('employee.name')->searchable(),
                TextColumn::make('benefit_id'),
                TextColumn::make('amount')->numeric(),
            ])
            ->filters([
                SelectFilter::make('Benefit')
                    ->relationship('benefit', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('Employee')
                    ->relationship('employee', 'first_name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                DetachAction::make(),

            ])
            ->emptyState(fn() => new HtmlString("No Benefits"));
    }


}

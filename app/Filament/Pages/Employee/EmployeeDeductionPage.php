<?php

namespace App\Filament\Pages\Employee;

use App\Enums\EmployeeStatusEnum;
use App\Models\Deduction;
use App\Models\Employee;
use App\Models\EmployeeDeduction;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Pages\Page;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Database\Eloquent\Builder as BuilderContract;
use Illuminate\Database\Eloquent\Builder;
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
                Filter::make('employee')
                    ->form([
                        Select::make('employee_id')
                        ->label('Employee')
                            ->options(function () : array {

                                $options = [];

                                foreach (Employee::query()->where('status', EmployeeStatusEnum::ACTIVE)->cursor() as $employee) {
                                    $options[$employee->id] = $employee->name;

                                }

                                return  $options;


                            })
                            ->searchable()
                            ->preload(),
                    ])

                    ->query(function (BuilderContract $query, array $data): BuilderContract {
                        return $query
                            ->when(
                                $data['employee_id'],
                                fn (Builder $query, $employeeId): BuilderContract => $query->where('employee_id', $employeeId),
                            );
                    })

            ])
            ->emptyState(fn() => new HtmlString("No Deduction"));
    }


}

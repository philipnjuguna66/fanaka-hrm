<?php

namespace App\Filament\Pages\Employee;

use App\Enums\EmployeeStatusEnum;
use App\Models\Benefit;
use App\Models\Deduction;
use App\Models\Employee;
use App\Models\EmployeeBenefit;
use App\Models\EmployeeDeduction;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;

class EmployeeDeductionPage extends Page implements HasTable
{
    use InteractsWithTable;

    use InteractsWithActions;


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
            ->headerActions([
                \Filament\Tables\Actions\Action::make('Add Deduction')
                    ->slideOver()
                    ->closeModalByClickingAway()
                ->form(fn(Form $form): Form => $form->schema([
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
                    Select::make('deduction_id')
                        ->label('Deduction')
                        ->options(function () : array {

                            $options = [];

                            foreach (Deduction::query()->cursor() as $deduction) {
                                $options[$deduction->id] = $deduction->name;

                            }

                            return  $options;


                        })
                        ->searchable()
                        ->preload(),
                    TextInput::make('amount')->required()->numeric(),
                ]))
                ->action(function (array $data){

                    EmployeeDeduction::updateOrCreate([
                        'employee_id' => $data['employee_id'],
                        'deduction_id' => $data['deduction_id'],
                    ],[
                        'amount' => $data['amount']
                    ]);

                    if (6 === $data['deduction_id'] )
                    {
                        EmployeeBenefit::updateOrCreate([
                            'employee_id' => $data['employee_id'],
                            'benefit_id' => Benefit::query()->where('code', 'cash-award')->firstOrCreate([
                                'name' => 'Cash Award',
                                'code' => 'cash-award',
                                'taxable' => true,
                                'non_cash' => false,
                                'mode' => "monthly",
                                'taxed_from_amount' => 0,
                                'type' => "fixed_amount",
                            ])->id,
                        ],[
                            'amount' => $data['amount']
                        ]);
                    }




                    return Notification::make('success')
                        ->success()
                        ->body('Updated')
                        ->send();
                })
            ])
            ->actions([

                \Filament\Tables\Actions\Action::make('edit')
                    ->slideOver()
                    ->closeModalByClickingAway(false)
                    ->mountUsing(fn(ComponentContainer $form , EmployeeDeduction $record) => $form->fill([
                        'amount' => $record->amount
                    ]))
                    ->form(fn(Form $form) : Form => $form->schema([
                        TextInput::make('amount')->required()->numeric(),
                    ]))
                    ->action(function (array $data,EmployeeDeduction $employeeDeduction){

                        $employeeDeduction->updateQuietly([
                            'amount' => $data['amount'],
                        ]);

                        if (6 === $employeeDeduction->deduction_id )
                        {
                            EmployeeBenefit::updateOrCreate([
                                'employee_id' => $employeeDeduction->employee_id,
                                'benefit_id' => Benefit::query()->where('code', 'cash-award')->firstOrCreate([
                                    'name' => 'Cash Award',
                                    'code' => 'cash-award',
                                    'taxable' => true,
                                    'non_cash' => false,
                                    'mode' => "monthly",
                                    'taxed_from_amount' => 0,
                                    'type' => "fixed_amount",
                                ])->id,
                            ],[
                                'amount' => $data['amount']
                            ]);
                        }



                        return Notification::make('success')
                            ->success()
                            ->body('Updated')
                            ->send();

                    }),

                \Filament\Tables\Actions\Action::make('delete')
                    ->requiresConfirmation()
                    ->action(function (  EmployeeDeduction $employeeDeduction){


                        try {
                            DB::beginTransaction();

                            if (6 === $employeeDeduction->deduction_id )
                            {
                                $benefit = \App\Models\Benefit::query()->where('code','cash_award')
                                    ->first();

                                if (! $benefit)
                                {
                                    $benefit = \App\Models\Benefit::create([
                                        'name' => 'Cash Award',
                                        'code' => 'cash-award',
                                        'taxable' => true,
                                        'non_cash' => false,
                                        'mode' => "monthly",
                                        'taxed_from_amount' => 0,
                                        'type' => "fixed_amount",
                                    ]);
                                }



                                EmployeeBenefit::query()
                                    ->where([
                                        'benefit_id' => $benefit->id,
                                        'employee_id' => $employeeDeduction->employee_id
                                    ])
                                    ->delete();
                            }

                            $employeeDeduction->deleteQuietly();

                            DB::commit();

                            return Notification::make('success')
                                ->success()
                                ->body('deleted')
                                ->send();
                        }
                        catch (\Exception $exception)
                        {
                            DB::rollBack();

                            return Notification::make('success')
                                ->success()
                                ->body($exception->getMessage())
                                ->send();

                        }




                    })

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

<?php

namespace App\Filament\Pages\Employee;

use App\Enums\EmployeeStatusEnum;
use App\Models\Deduction;
use App\Models\Employee;
use App\Models\EmployeeBenefit;
use App\Models\EmployeeDeduction;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\EditAction;
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
use Illuminate\Support\HtmlString;

class EmployeeBenefitPage extends Page implements HasTable
{
    use InteractsWithTable;

    use InteractsWithActions;

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
                TextColumn::make('benefit.name'),
                TextColumn::make('amount')->numeric(),
            ])
            ->filters([
                SelectFilter::make('Benefit')
                    ->relationship('benefit', 'name')
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
            ->actions([

                \Filament\Tables\Actions\Action::make('edit')
                    ->slideOver()
                    ->closeModalByClickingAway(false)
                    ->form(fn(Form $form) : Form => $form->schema([
                        TextInput::make('amount')->required()->numeric(),
                    ]))
                    ->action(function (array $data,EmployeeBenefit $employeeBenefit){

                        $employeeBenefit->updateQuietly([
                            'amount' => $data['amount'],
                        ]);



                        return Notification::make('success')
                            ->success()
                            ->body('Updated')
                            ->send();

                    }),

                \Filament\Tables\Actions\Action::make('delete')
                    ->requiresConfirmation()
                    ->action(function (  EmployeeBenefit $employeeBenefit){

                        $employeeBenefit->deleteQuietly();

                        return Notification::make('success')
                            ->success()
                            ->body('deleted')
                            ->send();

                    })

            ])
            ->headerActions([
                \Filament\Tables\Actions\Action::make('Add Deduction')
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

                    })
            ])
            ->emptyState(fn() => new HtmlString("No Benefits"));
    }


}

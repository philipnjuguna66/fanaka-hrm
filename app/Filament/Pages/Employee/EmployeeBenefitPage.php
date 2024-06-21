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
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInput;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Database\Eloquent\Builder as BuilderContract;
use Illuminate\Contracts\Queue\QueueableCollection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
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
                TextColumn::make('employee.first_name')
                    ->getStateUsing(fn(EmployeeBenefit $record) => $record->employee->name)->searchable(),
                TextColumn::make('benefit.name'),
                TextInput::make('amount')->numeric(),
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
                    ->mountUsing(fn(ComponentContainer $form , EmployeeBenefit $record) => $form->fill([
                        'amount' => $record->amount
                    ]))
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
            ->bulkActions([
                BulkAction::make('remove')
                ->requiresConfirmation()
                ->action(fn (Collection $records) => $records->each->delete())
                ->deselectRecordsAfterCompletion()
            ])
            ->headerActions([
                \Filament\Tables\Actions\Action::make('Add Benefit')
                    ->label('Add Benefit')
                    ->slideOver()
                    ->closeModalByClickingAway(false)
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
                        Select::make('benefit_id')
                            ->label('Benefit')
                            ->options(function () : array {

                                $options = [];

                                foreach (Benefit::query()->cursor() as $deduction) {
                                    $options[$deduction->id] = $deduction->name;

                                }

                                return  $options;


                            })
                            ->searchable()
                            ->preload(),
                        TextInput::make('amount')->required()->numeric(),
                    ]))
                    ->action(function (array $data){

                        EmployeeBenefit::updateOrCreate([
                            'employee_id' => $data['employee_id'],
                            'benefit_id' => $data['benefit_id'],
                        ],[
                            'amount' => $data['amount']
                        ]);

                        return Notification::make('success')
                            ->success()
                            ->body('Updated')
                            ->send();
                    })
            ])
            ->emptyState(fn() => new HtmlString("No Benefits"));
    }


}

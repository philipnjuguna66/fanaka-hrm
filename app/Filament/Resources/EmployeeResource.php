<?php

namespace App\Filament\Resources;

use App\Enums\EmployeeStatusEnum;
use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Filament\Resources\EmployeeResource\RelationManagers\EmployeeBenefitsRelationManager;
use App\Models\Employee;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = "HR";

    public static function getGloballySearchableAttributes(): array
    {
        return ['first_name', 'middle_name','last_name'];
    }
#Multi
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Position' => $record->loadMissing('jobTitle')?->jobTitle?->title,
            'Name' => $record->name,
        ];
    }
    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->name;
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema(
                [
                Forms\Components\Wizard::make([
                    Forms\Components\Wizard\Step::make('Personal Details')->schema(Pages\CreateEmployee::personalDetails()),
                    Forms\Components\Wizard\Step::make('Salary Details')->schema(Pages\CreateEmployee::salaryDetails()),
                    Forms\Components\Wizard\Step::make('Hr Details')->schema(Pages\CreateEmployee::hrDetails()),
                    Forms\Components\Wizard\Step::make('Contact Details')->schema(Pages\CreateEmployee::contactDetailsForm()),
                  ])->columnSpanFull()
                ]
            );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('photo')->label('Passport')->square(),
                Tables\Columns\TextColumn::make('first_name')->label('First Name')
                    ->wrapHeader()
                    ->searchable(),
                Tables\Columns\TextColumn::make('middle_name')
                    ->label('Middle Name')
                    ->wrapHeader()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->label('Last Name')
                    ->wrapHeader()
                    ->searchable(),
                Tables\Columns\TextColumn::make('legal_document_number')
                    ->label('legal_document_number')
                    ->wrapHeader()
                    ->searchable(),
                Tables\Columns\TextColumn::make('hrDetail.staff_number')
                    ->label('Staff No.')
                    ->searchable(),
                Tables\Columns\TextColumn::make('hrDetail.staff_number')
                    ->label('Staff No.')
                    ->searchable(),
                Tables\Columns\TextColumn::make('hrDetail.jobTitle.label')
                    ->label('JobTitle')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('hrDetail.department.title')
                    ->label('Department')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('gender')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('residential_status')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->wrapHeader()
                    ->searchable(),
                Tables\Columns\TextColumn::make('legal_document_type')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Legal Doc Type')
                    ->wrapHeader()
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('legal_document_number')
                    ->toggleable(isToggledHiddenByDefault: true)

                    ->label('Legal Doc No.')
                    ->wrapHeader()
                    ->searchable(),
                Tables\Columns\TextColumn::make('kra_pin_no')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('nssf_no')
                    ->toggleable(isToggledHiddenByDefault: true)

                    ->searchable(),
                Tables\Columns\TextColumn::make('nhif_no')
                    ->toggleable(isToggledHiddenByDefault: true)

                    ->searchable(),
                Tables\Columns\TextColumn::make('marital_status')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('nationality')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([

                SelectFilter::make('jobTitle')
                    ->label('Job Title')
                    ->preload(true)
                    ->searchable()
                    ->relationship('hrDetail.jobTitle', 'label'),
                SelectFilter::make('department')
                    ->label('Department')
                    ->preload(true)
                    ->searchable()
                    ->relationship('hrDetail.department', 'title'),
            ])
            ->actions([
               Tables\Actions\ActionGroup::make([
                   Tables\Actions\EditAction::make()->modalSubmitAction(false)->icon(null),
                   Tables\Actions\Action::make('Deactivate')
                       ->visible(fn(Employee $employee) : bool => $employee->status == EmployeeStatusEnum::ACTIVE)
                       ->action(fn(Employee $employee) => $employee->updateQuietly(['status' => EmployeeStatusEnum::NOT_ACTIVE])),
                   Tables\Actions\Action::make('Activate')
                       ->visible(fn(Employee $employee) : bool => $employee->status == EmployeeStatusEnum::NOT_ACTIVE)
                       ->action(fn(Employee $employee) => $employee->updateQuietly(['status' => EmployeeStatusEnum::ACTIVE]))
               ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->striped();
    }

    public static function getRelations(): array
    {
        return [
            EmployeeBenefitsRelationManager::class,
            RelationManagers\EmployeeDeductionsRelationManager::class,
           // RelationManagers\CarBenefitsRelationManager::class,
           // RelationManagers\HousingBenefitsRelationManager::class,
            RelationManagers\PayslipsRelationManager::class,
        ];
    }

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        return self::getUrl('edit', ['record' => $record]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources\EmployeeResource\RelationManagers;

use App\Filament\Resources\BenefitResource;
use App\Models\Benefit;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeBenefitsRelationManager extends RelationManager
{
    protected static string $relationship = 'employeeBenefits';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('benefit_id')
                    ->required()
                    ->relationship('benefit','name')
                    ->createOptionForm(BenefitResource::createForm())
                    ->createOptionModalHeading('New Benefit Details'),
                Forms\Components\Select::make('type')
                    ->options([
                        'fixed_amount' => "Fixed Amount",
                        'percentage' => 'Percentage',
                    ])->reactive(),
                Forms\Components\Select::make('percentage_of')
                    ->options([
                        'basic_salary' => "Basic Salary",
                    ])->visible(fn(Forms\Get $get) => $get('type') == 'percentage'),
                Forms\Components\TextInput::make('fixed_amount')
                    ->visible(fn(Forms\Get $get) => $get('type') == 'fixed_amount'),
                Forms\Components\TextInput::make('percentage_value')->visible(fn(Forms\Get $get) => $get('type') == 'percentage'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('amount'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
//                Tables\Actions\CreateAction::make()->form(BenefitResource::createForm()),createForm
                Tables\Actions\AttachAction::make()
                    ->form(fn (AttachAction $action): array => [
                        $action->getRecordSelect()
                            ->required()->afterStateUpdated(function (?string $old,?string $state,Forms\Set $set,RelationManager $livewire){
                            $benefit = Benefit::find((int) $state);

                            $set('amount',$benefit->default($livewire->getOwnerRecord()));

                        })->reactive(),
                        Forms\Components\TextInput::make('amount')->required(),
                    ])->preloadRecordSelect(),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}

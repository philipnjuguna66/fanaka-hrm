<?php

namespace App\Filament\Resources\EmployeeResource\RelationManagers;

use App\Filament\Resources\DeductionResource;
use App\Filament\Resources\DeductionTypeResource;
use App\Models\Deduction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeDeductionsRelationManager extends RelationManager
{
    protected static string $relationship = 'employeeDeductions';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('amount')->required(),
                ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('deductionType.name'),
                Tables\Columns\TextColumn::make('amount'),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->form(fn (AttachAction $action): array => [
                    $action->getRecordSelect()->afterStateUpdated(function (?string $old,?string $state,Forms\Set $set,RelationManager $livewire){
                        $deduction = Deduction::find((int) $state);
                        $set('amount',$deduction->default($livewire->getOwnerRecord()));
                    })->reactive(),
                    Forms\Components\TextInput::make('amount')->required()->minValue(1),
                ])->preloadRecordSelect(),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([

                ]),
            ]);
    }
}

<?php

namespace App\Filament\Resources\PayrollResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PayrollLinesRelationManager extends RelationManager
{
    protected static string $relationship = 'payrollLines';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('employee.first_name')
            ->columns([
                Tables\Columns\TextColumn::make('employee.first_name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('basic_pay')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gross_pay')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tax_allowable_deductions')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('car_benefits')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('housing_benefits')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('taxable_income')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nhif')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nssf')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('paye')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('personal_relief')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('insurance_relief')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('net_payee')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('net_pay')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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

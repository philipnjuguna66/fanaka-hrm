<?php

namespace App\Filament\Resources\PayrollResource\RelationManagers;

use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Models\Benefit;
use App\Models\Deduction;
use App\Models\StatutoryDeduction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
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
                Tables\Columns\TextColumn::make('employee.name')
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
                Tables\Columns\TextColumn::make('taxable_income')
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
                FilamentExportHeaderAction::make('export'),

             //   Tables\Actions\CreateAction::make(),
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

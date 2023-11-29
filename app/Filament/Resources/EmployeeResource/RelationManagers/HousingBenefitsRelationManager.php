<?php

namespace App\Filament\Resources\EmployeeResource\RelationManagers;

use App\Models\HousingBenefitType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HousingBenefitsRelationManager extends RelationManager
{
    protected static string $relationship = 'housingBenefits';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('housing_benefit_type_id')
                    ->label('Housing Benefit Type')
                    ->options(HousingBenefitType::query()->pluck('name','id'))
                    ->live()
                    ->required(),
                Forms\Components\TextInput::make('fair_market_rent_value')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('rent_recovered')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('rate')
                    ->helperText('This field is filled when the benefit IS  CALCULATED using a percentage of gross pay.')
                    ->required(fn(Forms\Get $get) => $get('housing_benefit_type_id') && HousingBenefitType::find($get('housing_benefit_type_id'))?->calculation_type == 'rate')
                    ->visible(fn(Forms\Get $get) => $get('housing_benefit_type_id') && HousingBenefitType::find($get('housing_benefit_type_id'))?->calculation_type == 'rate')
                    ->numeric(),
                Forms\Components\TextInput::make('actual_rent_value')
                    ->helperText('This field should be filled when the benefit IS  CALCULATED using actual rent value')
                    ->requiredWith('fair_market_rent_value')
                    ->required(fn(Forms\Get $get) => $get('housing_benefit_type_id') && HousingBenefitType::find($get('housing_benefit_type_id'))?->calculation_type == 'actual_rent_value')
                    ->visible(fn(Forms\Get $get) => $get('housing_benefit_type_id') && HousingBenefitType::find($get('housing_benefit_type_id'))?->calculation_type == 'actual_rent_value')
                    ->numeric(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('type')
            ->columns([
                Tables\Columns\TextColumn::make('housingBenefitType.name')->label('Benefit Type '),
                Tables\Columns\TextColumn::make('housingBenefitType.calculation_type')->label('Calculation Type '),
                Tables\Columns\TextColumn::make('actual_rent_value'),
                Tables\Columns\TextColumn::make('fair_market_rent_value'),
                Tables\Columns\TextColumn::make('rent_recovered'),
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

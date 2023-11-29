<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HousingBenefitTypeResource\Pages;
use App\Filament\Resources\HousingBenefitTypeResource\RelationManagers;
use App\Models\HousingBenefitType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HousingBenefitTypeResource extends Resource
{
    protected static ?string $model = HousingBenefitType::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'Payroll';


    protected static bool $shouldRegisterNavigation =  false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('calculation_type')
                    ->options([
                        'rate' => 'Percentage of Gross',
                        'actual_rent_value' => 'Actual Rent Value',
                    ])
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('calculation_type')
                    ->description('')
                    ->searchable(),
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
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHousingBenefitTypes::route('/'),
            'create' => Pages\CreateHousingBenefitType::route('/create'),
            'view' => Pages\ViewHousingBenefitType::route('/{record}'),
            'edit' => Pages\EditHousingBenefitType::route('/{record}/edit'),
        ];
    }
}

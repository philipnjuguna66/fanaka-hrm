<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeductionTypeResource\Pages;
use App\Filament\Resources\DeductionTypeResource\RelationManagers;
use App\Filament\Resources\DeductionTypeResource\RelationManagers\StatutoryDeductionsRelationManager;
use App\Models\DeductionType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DeductionTypeResource extends Resource
{
    protected static ?string $model = DeductionType::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder-minus';

    protected static ?string $navigationGroup = 'Payroll';


    public static function form(Form $form): Form
    {
        return $form
            ->schema(self::createForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('mode')
                    ->searchable(),
                Tables\Columns\IconColumn::make('tax_allowable')
                    ->boolean(),
                Tables\Columns\IconColumn::make('tax_relief')
                    ->boolean(),
                Tables\Columns\IconColumn::make('capped')
                    ->boolean(),
                Tables\Columns\TextColumn::make('cap_limit')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('statutoryDeductions.name')
                    ->label('Statutory Deductions')
                    ->listWithLineBreaks(),
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
            StatutoryDeductionsRelationManager::class

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDeductionTypes::route('/'),
            'create' => Pages\CreateDeductionType::route('/create'),
            'view' => Pages\ViewDeductionType::route('/{record}'),
            'edit' => Pages\EditDeductionType::route('/{record}/edit'),
        ];
    }

    public static function createForm()
    {

        return [
            Forms\Components\TextInput::make('name')
                ->required()
                ->unique('deduction_types',ignoreRecord: true)
                ->maxLength(255),
            Forms\Components\TextInput::make('code')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('mode')
                ->required()
                ->maxLength(255),
            Forms\Components\Toggle::make('tax_allowable')
                ->required(),
            Forms\Components\Toggle::make('tax_relief')
                ->required(),
            Forms\Components\Toggle::make('capped')
                ->required(),
            Forms\Components\TextInput::make('cap_limit')
                ->required()
                ->numeric()
                ->default(0),
        ];
    }
}

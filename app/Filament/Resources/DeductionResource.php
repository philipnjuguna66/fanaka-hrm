<?php

namespace App\Filament\Resources;

use App\Enums\DeductionPercentageOf;
use App\Filament\Resources\DeductionResource\Pages;
use App\Models\Deduction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;


class DeductionResource extends Resource
{
    protected static ?string $model = Deduction::class;

    protected static ?string $navigationIcon = 'heroicon-o-minus-circle';

    protected static ?string $navigationGroup = 'Payroll';


    public static function form(Form $form): Form
    {
        return $form
            ->schema(self::formSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('deductionType.name')
                    ->badge()
                    ->label('Deduction Type')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fixed_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('percentage_of')
                    ->badge()
                    ->color(fn ($state): string => match ($state) {
                        DeductionPercentageOf::BasicSalary => 'info',
                        DeductionPercentageOf::GrossSalary => 'warning',
                        default =>'danger',
                    })
                    ->formatStateUsing(fn($state) => str($state->value)->headline())
                    ->searchable(),
                Tables\Columns\TextColumn::make('percentage_value')
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
            'index' => Pages\ListDeductions::route('/'),
            'create' => Pages\CreateDeduction::route('/create'),
            'view' => Pages\ViewDeduction::route('/{record}'),
            'edit' => Pages\EditDeduction::route('/{record}/edit'),
        ];
    }

    public static function formSchema()
    {
        return [
            Forms\Components\Select::make('deduction_type_id')
                ->required()
                ->relationship('deductionType','name'),
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
            Forms\Components\Select::make('type')
                ->required()
                ->selectablePlaceholder(fn(string $context) => $context === 'create')
                ->required()
                ->options([
                    'percentage' => "Percentage",
                    'fixed_amount' => "Fixed Amount",
                ])->live(),
            Forms\Components\Select::make('percentage_of')
                ->selectablePlaceholder(fn(string $context) => $context === 'create')
                ->options(DeductionPercentageOf::getKeyValueOptions())->visible(fn(Forms\Get $get) => $get('type') == 'percentage')
                ->required(fn(Forms\Get $get) => $get('type') == 'percentage'),
            Forms\Components\TextInput::make('fixed_amount')
                ->visible(fn(Forms\Get $get) => $get('type') == 'fixed_amount')
                ->required(fn(Forms\Get $get) => $get('type') == 'fixed_amount'),
            Forms\Components\TextInput::make('percentage_value')
                ->maxValue(99)
                ->visible(fn(Forms\Get $get) => $get('type') == 'percentage')
                ->required(fn(Forms\Get $get) => $get('type') == 'percentage')
        ];
    }
}

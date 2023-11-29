<?php

namespace App\Filament\Resources;

use App\Enums\Modes;
use App\Filament\Resources\BenefitResource\Pages;
use App\Models\Benefit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;


class BenefitResource extends Resource
{
    protected static ?string $model = Benefit::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift';

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
                Tables\Columns\IconColumn::make('taxable')
                    ->boolean(),
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\IconColumn::make('non_cash')
                    ->boolean(),
                Tables\Columns\TextColumn::make('mode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('taxed_from_amount')
                    ->numeric()
                    ->sortable()
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListBenefits::route('/'),
            'create' => Pages\CreateBenefit::route('/create'),
            'edit' => Pages\EditBenefit::route('/{record}/edit'),
        ];
    }

    public static function createForm()
    {
        return [
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('code')
                ->required()
                ->maxLength(255),
          Forms\Components\Fieldset::make()->schema([
              Forms\Components\Toggle::make('taxable')
                  ->required(),
              Forms\Components\Toggle::make('non_cash')
                  ->required(),
          ]),
            Forms\Components\Select::make('mode')
                ->required()
                ->options(Modes::getKeyValueOptions()),
            Forms\Components\TextInput::make('taxed_from_amount')
                ->nullable()
                ->numeric(),

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
                ->options([
                    'basic_salary' => "Basic Salary",
                ])->visible(fn(Forms\Get $get) => $get('type') == 'percentage')
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

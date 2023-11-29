<?php

namespace App\Filament\Resources;

use App\Enums\DeductionPercentageOf;
use App\Filament\Resources\StatutoryDeductionResource\Pages;
use App\Models\StatutoryDeduction;
use Closure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;


class StatutoryDeductionResource extends Resource
{
    protected static ?string $model = StatutoryDeduction::class;

    protected static ?string $navigationIcon = 'heroicon-o-flag';

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
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
//                Tables\Columns\TextColumn::make('ranges')
//                    ->listWithLineBreaks()
//                    ->limitList(3),
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
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStatutoryDeductions::route('/'),
            'create' => Pages\CreateStatutoryDeduction::route('/create'),
            'view' => Pages\ViewStatutoryDeduction::route('/{record}'),
            'edit' => Pages\EditStatutoryDeduction::route('/{record}/edit'),
        ];
    }

    public static function formSchema(): array
    {
       return [
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
           Forms\Components\TextInput::make('maximum')
               ->required()
               ->numeric(),
            Forms\Components\Repeater::make('ranges')
                ->schema([
                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\TextInput::make('min_range')->numeric(),
                        Forms\Components\TextInput::make('max_range')->numeric(),
                        Forms\Components\Select::make('type')
                            ->required()
                            ->searchable()
                            ->required()
                            ->options([
                                'percentage' => "Percentage",
                                'fixed_amount' => "Fixed Amount",
                            ])
                            ->default('fixed_amount')
                            ->live(),
                        Forms\Components\TextInput::make('deduction')
                            ->visible()
                            ->required(),

                    ])
                ])->columnSpanFull()
                ->rules([
                    function () {
                        return function (string $attribute, $value, Closure $fail) {
                            if (collect($value)->flatten()->filter()->isEmpty()) {
                                $fail('The ranges cannot be empty');
                            }
                        };
                    },
                ])
                ->required(),
        ];
    }
}

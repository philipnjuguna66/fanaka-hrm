<?php

namespace App\Filament\Resources\EmployeeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CarBenefitsRelationManager extends RelationManager
{
    protected static string $relationship = 'carBenefits';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(4)->schema([
                    Forms\Components\TextInput::make('car_reg_no')
                        ->label('Car Registration Number')
                        ->required(),
                    Forms\Components\TextInput::make('make')->required(),
                    Forms\Components\TextInput::make('cc_rating')
                        ->required(),
                    Forms\Components\Select::make('body_type')
                        ->options([
                            'Pick Ups, Panel Vans Uncovered' => "Pick Ups, Panel Vans Uncovered",
                            'Saloon Hatch Backs and Estates' => "Saloon Hatch Backs and Estates",
                            'Land Rovers/ Cruisers(excludes Range Rovers and vehicles of similar nature)' => "Land Rovers/ Cruisers(excludes Range Rovers and vehicles of similar nature)"
                        ])
                        ->required(),
                    Forms\Components\Select::make('type_of_car_cost')
                        ->options([
                            'owned'=> 'owned',
                            'hired' => 'hired'
                        ])->reactive()
                        ->required(),
                    Forms\Components\TextInput::make('cost_of_owned_car')
                        ->numeric()
                        ->visible(fn(callable $get) => $get('type_of_car_cost') == 'owned')
                        ->required(fn(callable $get) => $get('type_of_car_cost') == 'owned'),
                    Forms\Components\TextInput::make('commissioner_rate')
                        ->numeric()
                        ->helperText('(Fixed amount from KRA)')
                        ->visible(fn(callable $get) => $get('type_of_car_cost') == 'owned')
                        ->required(fn(callable $get) => $get('type_of_car_cost') == 'owned'),
                    Forms\Components\TextInput::make('benefit_rate')
                        ->numeric()
                        ->helperText('Benefit rate %')
                        ->visible(fn(callable $get) => $get('type_of_car_cost') == 'owned')
                        ->required(fn(callable $get) => $get('type_of_car_cost') == 'owned'),
                    Forms\Components\TextInput::make('cost_of_hiring')
                        ->numeric()
                        ->visible(fn(callable $get) => $get('type_of_car_cost') == 'hired')
                        ->required(fn(callable $get) => $get('type_of_car_cost') == 'hired'),
                ])

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('car_reg_no')
            ->columns([
                Tables\Columns\TextColumn::make('car_reg_no'),
                Tables\Columns\TextColumn::make('make')->wrap(),
                Tables\Columns\TextColumn::make('body_type')->wrap(),
                Tables\Columns\TextColumn::make('cc_rating'),
                Tables\Columns\TextColumn::make('type_of_car_cost')->badge(),
                Tables\Columns\TextColumn::make('cost_of_owned_car'),
                Tables\Columns\TextColumn::make('cost_of_hiring'),
                Tables\Columns\TextColumn::make('commissioner_rate'),
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

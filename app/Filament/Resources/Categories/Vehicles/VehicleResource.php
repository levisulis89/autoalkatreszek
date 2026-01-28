<?php

namespace App\Filament\Resources\Vehicles;
use App\Filament\Resources\Vehicles\Pages;
use App\Models\Vehicle;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use BackedEnum;
use Filament\Support\Icons\Heroicon;


class VehicleResource extends Resource
{
    protected static ?string $model = Vehicle::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTruck;


    protected static ?string $recordTitleAttribute = 'model';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('make')
                ->label('Márka')
                ->required()
                ->maxLength(100),

            TextInput::make('model')
                ->label('Modell')
                ->required()
                ->maxLength(100),

            TextInput::make('engine')
                ->label('Motor')
                ->maxLength(100),

            TextInput::make('year_from')
                ->label('Évjárat (tól)')
                ->numeric(),

            TextInput::make('year_to')
                ->label('Évjárat (ig)')
                ->numeric(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('make')->label('Márka')->searchable(),
            Tables\Columns\TextColumn::make('model')->label('Modell')->searchable(),
            Tables\Columns\TextColumn::make('engine')->label('Motor'),
            Tables\Columns\TextColumn::make('year_from')->label('Tól'),
            Tables\Columns\TextColumn::make('year_to')->label('Ig'),
        ])
        ->actions([]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListVehicles::route('/'),
            'create' => Pages\CreateVehicle::route('/create'),
            'edit'   => Pages\EditVehicle::route('/{record}/edit'),
        ];
    }
}

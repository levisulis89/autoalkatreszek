<?php

namespace App\Filament\Resources\Products\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Tables;
use Filament\Tables\Table;

class StocksRelationManager extends RelationManager
{
    protected static string $relationship = 'stocks';

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            Grid::make(2)->schema([
                Forms\Components\TextInput::make('qty')
                    ->label('Készlet (db)')
                    ->numeric()
                    ->required(),

                Forms\Components\TextInput::make('lead_days')
                    ->label('Szállítási napok')
                    ->numeric()
                    ->default(1)
                    ->required(),
            ]),
        ]);
    }

    public function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('qty')->label('Készlet')->sortable(),
            Tables\Columns\TextColumn::make('lead_days')->label('Nap')->sortable(),
            Tables\Columns\TextColumn::make('updated_at')->since()->label('Frissítve'),
        ]);
}


    protected function getTableRecordUrl($record): ?string
    {
        return null;
    }
}

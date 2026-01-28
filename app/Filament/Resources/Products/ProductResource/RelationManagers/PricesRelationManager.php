<?php

namespace App\Filament\Resources\Products\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class PricesRelationManager extends RelationManager
{
    protected static string $relationship = 'prices';

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            Forms\Components\TextInput::make('currency')
                ->label('Deviza')
                ->default('HUF')
                ->required()
                ->maxLength(3),

            Forms\Components\TextInput::make('gross')
                ->label('Bruttó ár')
                ->numeric()
                ->required(),

            Forms\Components\TextInput::make('net')
                ->label('Nettó ár')
                ->numeric()
                ->nullable(),

            Forms\Components\DateTimePicker::make('valid_from')
                ->label('Érvényes ettől')
                ->default(now())
                ->required(),

            Forms\Components\DateTimePicker::make('valid_to')
                ->label('Érvényes eddig')
                ->nullable(),
        ])->columns(2);
    }

    public function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('gross')
                ->label('Bruttó')
                ->sortable(),

            Tables\Columns\TextColumn::make('currency')
                ->label('Deviza'),

            Tables\Columns\TextColumn::make('valid_from')
                ->label('Ettől')
                ->dateTime(),

            Tables\Columns\TextColumn::make('valid_to')
                ->label('Eddig')
                ->dateTime(),

            Tables\Columns\TextColumn::make('updated_at')
                ->label('Frissítve')
                ->since(),
        ]);
}
}
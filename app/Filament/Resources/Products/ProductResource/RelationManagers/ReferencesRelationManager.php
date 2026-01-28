<?php

namespace App\Filament\Resources\Products\ProductResource\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class ReferencesRelationManager extends RelationManager
{
    protected static string $relationship = 'references';

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            Forms\Components\Select::make('type')
                ->label('Típus')
                ->options([
                    'OEM' => 'OEM',
                    'ALT' => 'ALT',
                    'EAN' => 'EAN',
                    'SUPPLIER' => 'SUPPLIER',
                ])
                ->required(),

            Forms\Components\TextInput::make('value')
                ->label('Érték')
                ->required()
                ->maxLength(255),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')->label('Típus')->badge(),
                Tables\Columns\TextColumn::make('value')->label('Érték')->searchable()->wrap(),
                Tables\Columns\TextColumn::make('updated_at')->label('Frissítve')->since(),
            ])
            ->headerActions([
                CreateAction::make()->label('Új referencia'),
            ])
            ->actions([
                EditAction::make()->label('Szerkesztés'),
                DeleteAction::make()->label('Törlés'),
            ]);
    }
}

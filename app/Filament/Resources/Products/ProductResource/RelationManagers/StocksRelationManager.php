<?php

namespace App\Filament\Resources\Products\ProductResource\RelationManagers;

use App\Models\Warehouse;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class StocksRelationManager extends RelationManager
{
    protected static string $relationship = 'stocks';

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            Forms\Components\Select::make('warehouse_id')
                ->label('Raktár')
                ->required()
                ->options(fn () => Warehouse::query()
                    ->orderBy('name')
                    ->get()
                    ->mapWithKeys(fn ($w) => [
                        $w->id => trim(($w->code ? $w->code . ' - ' : '') . $w->name),
                    ])
                    ->toArray()
                )
                ->searchable()
                ->preload()
                ->default(fn () => Warehouse::query()->value('id')),

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
                Tables\Columns\TextColumn::make('warehouse.name')
                    ->label('Raktár')
                    ->sortable(),

                Tables\Columns\TextColumn::make('qty')
                    ->label('Készlet')
                    ->sortable(),

                Tables\Columns\TextColumn::make('lead_days')
                    ->label('Nap')
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->since()
                    ->label('Frissítve'),
            ])
            ->headerActions([
                CreateAction::make()->label('Új készlet'),
            ])
            ->actions([
                EditAction::make()->label('Szerkesztés'),
                DeleteAction::make()->label('Törlés'),
            ]);
    }

    protected function getTableRecordUrl($record): ?string
    {
        return null;
    }
}

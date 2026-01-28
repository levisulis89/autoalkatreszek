<?php

namespace App\Filament\Resources\Brands\Tables;

use Filament\Tables;
use Filament\Tables\Table;

class BrandsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Márka')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Létrehozva')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->actions([])
            ->bulkActions([]);
            
    }
}

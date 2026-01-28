<?php

namespace App\Filament\Resources\Brands\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class BrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('name')
                ->label('Márkanév')
                ->required()
                ->maxLength(255)
                ->live(onBlur: true)
                ->afterStateUpdated(function ($state, callable $set) {
                    $set('slug', Str::slug($state));
                }),

            TextInput::make('slug')
                ->label('Slug')
                ->required()
                ->maxLength(255)
                ->disabled()
                ->dehydrated()
                ->unique(ignoreRecord: true),
        ]);
    }
}

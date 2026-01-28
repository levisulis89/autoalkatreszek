<?php

namespace App\Filament\Resources\Categories;

use App\Filament\Resources\Categories\Pages;
use App\Models\Category;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;


class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Select::make('parent_id')
                ->label('Szülő')
                ->options(fn () => Category::query()->orderBy('name')->pluck('name', 'id')->toArray())
                ->searchable()
                ->preload()
                ->nullable(),

            TextInput::make('name')
                ->label('Név')
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Név')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('parent.name')
                    ->label('Szülő')
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->sortable(),
            ])
            ->recordUrl(fn ($record) => static::getUrl('edit', ['record' => $record]));
    }

    public static function getPages(): array
{
    return [
        'index'  => Pages\ListCategories::route('/'),
        'create' => Pages\CreateCategory::route('/create'),
        'edit'   => Pages\EditCategory::route('/{record}/edit'),
    ];
}
}
<?php

namespace App\Filament\Resources\Products;

use App\Filament\Resources\Products\Pages;
use App\Filament\Resources\Products\ProductResource\RelationManagers;
use App\Models\Product;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;


    protected static string|\UnitEnum|null $navigationGroup = 'Katalógus';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Alapadatok')
                ->schema([
                    Grid::make(2)->schema([
                        Forms\Components\TextInput::make('sku')
                            ->label('SKU / Cikkszám')
                            ->required()
                            ->maxLength(120)
                            ->unique(ignoreRecord: true),

                        Forms\Components\TextInput::make('oem_number')
                            ->label('OEM szám')
                            ->maxLength(120),
                    ]),

                    Forms\Components\TextInput::make('name')
                        ->label('Terméknév')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->maxLength(255)
                        ->helperText('Most kézzel. Kövi kör: automatikus slug a névből.'),

                    Grid::make(2)->schema([
                        Forms\Components\Select::make('brand_id')
                            ->label('Márka')
                            ->relationship('brand', 'name')
                            ->searchable()
                            ->preload(),

                        Forms\Components\Select::make('category_id')
                            ->label('Kategória')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload(),
                    ]),

                    Forms\Components\Toggle::make('is_active')
                        ->label('Aktív')
                        ->default(true),

                    Forms\Components\Textarea::make('description')
                        ->label('Leírás')
                        ->rows(5),
                ]),

            Section::make('Attribútumok')
                ->schema([
                    Forms\Components\Repeater::make('attributes')
                        ->label('Tulajdonságok')
                        ->default([])
                        ->schema([
                            Forms\Components\TextInput::make('key')->label('Kulcs')->required(),
                            Forms\Components\TextInput::make('value')->label('Érték')->required(),
                        ])
                        ->columns(2)
                        ->addActionLabel('Új attribútum')
                        ->collapsible(),
                ]),

            Section::make('Kompatibilitás')
                ->schema([
                    Forms\Components\Select::make('vehicles')
                        ->label('Kompatibilis járművek')
                        ->multiple()
                        ->relationship('vehicles', 'id')
                        ->getOptionLabelFromRecordUsing(fn ($r) =>
                            $r
                                ? trim("{$r->make} {$r->model} {$r->engine}")
                                    . ' (' . ($r->year_from ?? '?') . '-' . ($r->year_to ?? '?') . ')'
                                : '—'
                        )
                        ->searchable()
                        ->preload(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Név')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('brand.name')
                    ->label('Márka')
                    ->sortable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategória')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktív')
                    ->boolean()
                    ->sortable(),
            ])
            ->actions([ ])
            ->recordUrl(fn ($record) => static::getUrl('edit', ['record' => $record]))
            ->defaultSort('id', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\PricesRelationManager::class,
            RelationManagers\StocksRelationManager::class,
            RelationManagers\ReferencesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit'   => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}

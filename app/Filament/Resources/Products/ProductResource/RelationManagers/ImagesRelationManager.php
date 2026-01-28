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

class ImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'images';
    protected static ?string $title = 'Képek';

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            Forms\Components\FileUpload::make('path')
    ->label('Kép')
    ->image()
    ->disk('public')
    ->directory('products')
    ->visibility('public')
    ->imageEditor()
    ->getUploadedFileNameForStorageUsing(fn ($file) =>
        str()->slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
        . '.' . $file->getClientOriginalExtension()
    )
    ->required(),
            Forms\Components\TextInput::make('sort')
                ->label('Sorrend')
                ->numeric()
                ->default(0),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort')
            ->reorderable('sort')
            ->columns([
                Tables\Columns\ImageColumn::make('path')->label('Kép')->disk('public'),
                Tables\Columns\TextColumn::make('sort')->label('Sorrend')->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->label('Frissítve')->since(),
                Tables\Columns\ImageColumn::make('path')
                ->label('Kép')
                ->state(fn ($record) => asset('storage/' . ltrim($record->path, '/')))
                ->square()
                ->extraImgAttributes(['class' => 'object-cover'])
                ->size(60),


            ])
            ->headerActions([
                CreateAction::make()->label('Új kép'),
            ])
            ->actions([
                EditAction::make()->label('Szerkesztés'),
                DeleteAction::make()->label('Törlés'),
            ]);
    }
}

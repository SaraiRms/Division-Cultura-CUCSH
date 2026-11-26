<?php

namespace App\Filament\Resources\DirectoryRectorias\Tables;

use App\Models\DirectoryRectoria;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use App\Filament\Support\Eliminacion;
use Filament\Support\Enums\FontWeight;

class DirectoryRectoriasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            
            ->columns([
                ImageColumn::make('photo_path')
                    ->label('Foto')
                    ->disk('public')
                    ->circular(),

                TextColumn::make('full_name')
                    ->label('Nombre')
                    ->weight(FontWeight::Medium)
                    ->tooltip(fn($record) => $record->full_name)
                    ->description(fn($record) => $record->cargo)
                    ->searchable(['full_name', 'cargo'])
                    ->sortable(),

                TextColumn::make('area')
                    ->label('Área')
                    ->searchable(),
            ])

            ->defaultSort('full_name')        
            ->filters([
                Eliminacion::filtroPapelera(DirectoryRectoria::class),
            ])

            ->recordActions([
                Eliminacion::grupo('el', 'integrante de Rectoría', 'full_name'),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    Eliminacion::eliminarVarios(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);
    }
}

<?php

namespace App\Filament\Resources\DirectoryRectorias\Tables;

use App\Models\DirectoryRectoria;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use App\Filament\Support\Eliminacion;

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
                    ->searchable()
                    ->sortable(),

                TextColumn::make('cargo')
                    ->label('Cargo')
                    ->searchable(),

                TextColumn::make('area')
                    ->label('Área')
                    ->searchable(),
            ])

            ->defaultSort('full_name')        
            ->filters([
                Eliminacion::filtroPapelera(DirectoryRectoria::class),
                TrashedFilter::make(),
            ])

            ->recordActions([
                ViewAction::make(),
                EditAction::make()->hidden(fn (DirectoryRectoria $record) => $record->trashed()),
                Eliminacion::eliminar('el', 'integrante de Rectoría', 'full_name'),
                Eliminacion::restaurar(),
                Eliminacion::eliminarDefinitivo(),
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

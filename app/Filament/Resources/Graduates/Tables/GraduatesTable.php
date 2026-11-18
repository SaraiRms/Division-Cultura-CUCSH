<?php

namespace App\Filament\Resources\Graduates\Tables;

use App\Models\Graduate;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Support\Eliminacion;

class GraduatesTable
{
    public static function configure(Table $table): Table
    {
        return $table

            ->columns([

                TextColumn::make('program.name')
                    ->label('Programa')
                    ->searchable()
                    ->wrap(),

                TextColumn::make('title')
                    ->label('Título')
                    ->placeholder('—'),

                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => Graduate::STATUS_LABELS[$state] ?? $state)
                    ->color(fn (string $state) => match ($state) {
                        'published' => 'success',
                        'pending' => 'warning',
                        default => 'danger',
                    }),

                TextColumn::make('uploader.name')
                    ->label('Subido por'),
            ])
            
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options(Graduate::STATUS_LABELS),
            ])

            ->filters([
                Eliminacion::filtroPapelera(Graduate::class),
                TrashedFilter::make(),
            ])

            ->recordActions([
                ViewAction::make(),
                EditAction::make()->hidden(fn (Graduate $record) => $record->trashed()),
                Eliminacion::eliminar('la', 'generación'),
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

<?php

namespace App\Filament\Resources\Graduates\Tables;

use App\Models\Graduate;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
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
                    ->label('Título'),

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
                Eliminacion::grupo('la', 'generación'),
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

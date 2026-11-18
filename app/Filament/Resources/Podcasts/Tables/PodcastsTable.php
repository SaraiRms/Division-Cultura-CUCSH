<?php

namespace App\Filament\Resources\Podcasts\Tables;

use App\Models\Podcast;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use  Filament\Tables\Filters\SelectFilter;
use App\Filament\Support\Eliminacion;



class PodcastsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            
            ->columns([

                TextColumn::make('title')
                    ->label('Episodio')
                    ->searchable()
                    ->limit(60),

                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => Podcast::STATUS_LABELS[$state] ?? $state)
                    ->color(fn (string $state) => $state === 'published' ? 'success' : 'gray'),

                TextColumn::make('published_at')
                    ->label('Publicado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->placeholder('—'),
            ])
            ->defaultSort('published_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options(Podcast::STATUS_LABELS),
            ])


            ->filters([
                Eliminacion::filtroPapelera(Podcast::class),
                TrashedFilter::make(),
            ])

            ->recordActions([
                ViewAction::make(),
                EditAction::make()->hidden(fn (Podcast $record) => $record->trashed()),
                Eliminacion::eliminar('el', 'episodio'),
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

<?php

namespace App\Filament\Resources\Events\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Models\Event;
use App\Filament\Support\Eliminacion;

class EventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

            TextColumn::make('title')
                ->label('Evento')
                ->searchable()
                ->limit(50),

            // ImageColumn::make('image_path')
            //     ->label('Imagen')
            //     ->disk('public')
            //     ->square(),

            TextColumn::make('starts_at')
                ->label('Fecha y Hora')
                ->dateTime('d/m/Y H:i')
                ->sortable(),
                
            TextColumn::make('status')
                ->label('Estado')
                ->badge()
                ->formatStateUsing(fn (string $state) => Event::STATUS_LABELS[$state] ?? $state)
                ->color(fn (string $state) => match ($state) {
                    'published' => 'success',
                    'cancelled' => 'danger',
                    default => 'gray',
                }),

            TextColumn::make('creator.name')
                ->label('Creado por')
    
            ])

            ->filters([
                Eliminacion::filtroPapelera(Event::class),])
            
            ->recordActions([
                ViewAction::make(),
                EditAction::make()->hidden(fn (Event $record) => $record->trashed()),
                Eliminacion::eliminar('el', 'evento'),
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

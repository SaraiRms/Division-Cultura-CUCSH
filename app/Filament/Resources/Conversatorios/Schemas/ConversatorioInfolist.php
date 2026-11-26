<?php

namespace App\Filament\Resources\Conversatorios\Schemas;

use App\Models\Conversatorio;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ConversatorioInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title')
                    ->label('Título'),
                
                TextEntry::make('speaker')
                    ->label('Ponente(s)'),

                TextEntry::make('description')
                    ->label('Descripción')
                    ->columnSpanFull(),

                TextEntry::make('scheduled_at')
                    ->label('Fecha y Hora')
                    ->dateTime(),

                TextEntry::make('stream_url')
                    ->label('Enlace de la transmisión (YouTube)'),

                TextEntry::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'scheduled' => 'Programado',
                        'live'      => 'En vivo',
                        'finished'  => 'Finalizado',
                        default     => $state,
                    })
                    ->color(fn (string $state) => match ($state) {
                        'live'      => 'success',
                        'scheduled' => 'warning',
                        'finished'  => 'info',
                        default     => 'gray',
                    }),

                TextEntry::make('creator.name')
                    ->label('Creado por'),

                TextEntry::make('created_at')
                    ->label('Creado el')
                    ->dateTime(),

                TextEntry::make('updated_at')
                    ->label('Última actualización')
                    ->dateTime(),

                TextEntry::make('deleted_at')
                    ->label('Eliminado el')
                    ->dateTime()
                    ->visible(fn (Conversatorio $record): bool => $record->trashed()),
            ]);
    }
}

<?php

namespace App\Filament\Resources\Events\Schemas;

use App\Models\Event;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class EventInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title')
                    ->label('Título'),

                TextEntry::make('description')
                    ->label('Descripción')
                    ->html()
                    ->columnSpanFull(),

                TextEntry::make('location')
                    ->label('Lugar')
                    ->placeholder('—'),

                ImageEntry::make('image_path')
                    ->label('Imagen')
                    ->disk('public')
                    ->square(),

                TextEntry::make('starts_at')
                    ->label('Fecha de inicio')
                    ->dateTime('d/m/Y H:i'),

                TextEntry::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => Event::STATUS_LABELS[$state] ?? $state)
                    ->color(fn (string $state) => match ($state) {
                        'published' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),

                TextEntry::make('creator.name')
                    ->label('Creado por'),

                TextEntry::make('created_at')
                    ->label('Creado el')
                    ->dateTime('d/m/Y H:i'),

                TextEntry::make('updated_at')
                    ->label('Última modificación')
                    ->dateTime('d/m/Y H:i'),
            ]);
    }
}

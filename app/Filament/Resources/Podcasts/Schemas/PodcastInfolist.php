<?php

namespace App\Filament\Resources\Podcasts\Schemas;

use App\Models\Podcast;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use PhpParser\Node\Stmt\Label;

class PodcastInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                ImageEntry::make('image_path')
                    ->label('Imagen')
                    ->placeholder('-'),
                
                TextEntry::make('title')
                    ->label('Título'),

                TextEntry::make('description')
                    ->label('Descripción')
                    ->placeholder('-')
                    ->columnSpanFull(),

                TextEntry::make('video_url')
                    ->label('Enlace del video'),

                TextEntry::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => Podcast::STATUS_LABELS[$state] ?? $state)
                    ->color(fn (string $state) => $state === 'published' ? 'success' : 'gray'),

                TextEntry::make('published_at')
                    ->label('Fecha de publicación')
                    ->dateTime()
                    ->placeholder('-'),

                TextEntry::make('creator.name')
                    ->label('Creado por')
                    ->placeholder('-'),

                TextEntry::make('created_at')
                    ->label('Fecha de creación')
                    ->dateTime()
                    ->placeholder('-'),

                TextEntry::make('updated_at')
                    ->label('Última actualización')
                    ->dateTime()
                    ->placeholder('-'),

                TextEntry::make('deleted_at')
                    ->label('Fecha de eliminación')
                    ->dateTime()
                    ->visible(fn (Podcast $record): bool => $record->trashed()),
            ]);
    }
}

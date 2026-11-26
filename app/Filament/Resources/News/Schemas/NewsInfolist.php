<?php

namespace App\Filament\Resources\News\Schemas;

use App\Models\News;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class NewsInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title')
                    ->label('Título'),

                TextEntry::make('slug')
                    ->label('Enlace'),

                TextEntry::make('summary')
                    ->label('Resumen'),

                TextEntry::make('body')
                    ->label('Contenido de la noticia')
                    ->html()
                    ->columnSpanFull(),

                ImageEntry::make('image_path')
                    ->label('Imagen'),

                IconEntry::make('is_featured')
                    ->label('Destacada')
                    ->boolean(),

                TextEntry::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => News::STATUS_LABELS[$state] ?? $state)
                    ->color(fn (string $state) => match ($state) {
                        'published' => 'success',
                        'pending'   => 'warning',
                        'rejected'  => 'danger',
                        default     => 'gray',
                    }),
                    
                TextEntry::make('published_at')
                    ->label('Fecha de publicación')
                    ->dateTime(),

                TextEntry::make('author.name')
                    ->label('Autor'),

                TextEntry::make('approver.name')
                    ->label('Aprobado por'),

                TextEntry::make('approved_at')
                    ->label('Aprobado el')
                    ->dateTime(),

                TextEntry::make('created_at')
                    ->label('Creado el')
                    ->dateTime(),

                TextEntry::make('updated_at')
                    ->label('Actualizado el')
                    ->dateTime(),

                TextEntry::make('deleted_at')
                    ->label('Fecha de eliminación')
                    ->dateTime()
                    ->visible(fn (News $record): bool => $record->trashed()),
            ]);
    }
}

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
                    ->label('Resumen')
                    ->placeholder('-'),

                TextEntry::make('body')
                    ->label('Cuerpo')
                    ->html()
                    ->placeholder('-')
                    ->columnSpanFull(),

                ImageEntry::make('image_path')
                    ->label('Imagen')
                    ->placeholder('-'),

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
                    ->dateTime()
                    ->placeholder('-'),

                TextEntry::make('author.name')
                    ->label('Autor')
                    ->placeholder('-'),

                TextEntry::make('approver.name')
                    ->label('Aprobado por')
                    ->placeholder('-'),

                TextEntry::make('approved_at')
                    ->label('Aprobado el')
                    ->dateTime()
                    ->placeholder('-'),

                TextEntry::make('created_at')
                    ->label('Creado el')
                    ->dateTime()
                    ->placeholder('-'),

                TextEntry::make('updated_at')
                    ->label('Actualizado el')
                    ->dateTime()
                    ->placeholder('-'),

                TextEntry::make('deleted_at')
                    ->label('Fecha de eliminación')
                    ->dateTime()
                    ->visible(fn (News $record): bool => $record->trashed()),
            ]);
    }
}

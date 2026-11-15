<?php

namespace App\Filament\Resources\Graduates\Schemas;

use App\Models\Graduate;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class GraduateInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('program.name')
                    ->label('Programa'),

                TextEntry::make('title')
                    ->label('Generación')
                    ->placeholder('-'),

                ImageEntry::make('image_path')
                    ->label('Imagen'),

                TextEntry::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => Graduate::STATUS_LABELS[$state] ?? $state)
                    ->color(fn (string $state) => match ($state) {
                        'published' => 'success',
                        'pending' => 'warning',
                        default => 'danger',
                    }),

                TextEntry::make('uploader.name')
                    ->label('Actualizado por')
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
                    ->label('Actualizado')
                    ->dateTime()
                    ->placeholder('-'),

                TextEntry::make('deleted_at')
                    ->label('Fecha de eliminación')
                    ->dateTime()
                    ->visible(fn (Graduate $record): bool => $record->trashed()),
            ]);
    }
}

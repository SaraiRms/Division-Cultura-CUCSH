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
                    ->label('Generación'),

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
                    ->label('Actualizado por'),

                TextEntry::make('approver.name')
                    ->label('Aprobado por'),
                    
                TextEntry::make('approved_at')
                    ->label('Aprobado el')
                    ->dateTime(),

                TextEntry::make('created_at')
                    ->label('Creado el')
                    ->dateTime(),

                TextEntry::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime(),

                TextEntry::make('deleted_at')
                    ->label('Fecha de eliminación')
                    ->dateTime()
                    ->visible(fn (Graduate $record): bool => $record->trashed()),
            ]);
    }
}

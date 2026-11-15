<?php

namespace App\Filament\Resources\DirectoryRectorias\Schemas;

use App\Models\DirectoryRectoria;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Infolists\Components\ImageEntry;

class DirectoryRectoriaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                
                ImageEntry::make('photo_path')
                    ->label('Imagen')
                    ->disk('public')
                    ->circular(),

                TextEntry::make('full_name')
                    ->label('Nombre Completo'),

                TextEntry::make('cargo')
                    ->label('Cargo')
                    ->placeholder('-'),

                TextEntry::make('area')
                    ->label('Área')
                    ->placeholder('-'),

                TextEntry::make('creator.name')
                    ->label('Creado por')
                    ->placeholder('—'),

                TextEntry::make('created_at')
                    ->label('Creado el')
                    ->dateTime()
                    ->placeholder('-'),

                TextEntry::make('updated_at')
                    ->label('Última actualización')
                    ->dateTime()
                    ->placeholder('-'),

                TextEntry::make('deleted_at')
                    ->label('Eliminado el')
                    ->dateTime()
                    ->visible(fn (DirectoryRectoria $record): bool => $record->trashed()),
            ]);
    }
}

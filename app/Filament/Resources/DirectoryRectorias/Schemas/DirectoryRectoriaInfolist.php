<?php

namespace App\Filament\Resources\DirectoryRectorias\Schemas;

use App\Models\DirectoryRectoria;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DirectoryRectoriaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('full_name'),
                TextEntry::make('cargo')
                    ->placeholder('-'),
                TextEntry::make('area')
                    ->placeholder('-'),
                TextEntry::make('photo_path')
                    ->placeholder('-'),
                TextEntry::make('created_by')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (DirectoryRectoria $record): bool => $record->trashed()),
            ]);
    }
}

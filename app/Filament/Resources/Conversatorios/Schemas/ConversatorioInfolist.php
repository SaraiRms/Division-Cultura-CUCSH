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
                TextEntry::make('title'),
                
                TextEntry::make('speaker')
                    ->placeholder('-'),

                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),

                TextEntry::make('scheduled_at')
                    ->dateTime(),

                TextEntry::make('stream_url')
                    ->placeholder('-'),

                TextEntry::make('status')
                    ->badge(),

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
                    ->visible(fn (Conversatorio $record): bool => $record->trashed()),

            ]);
    }
}

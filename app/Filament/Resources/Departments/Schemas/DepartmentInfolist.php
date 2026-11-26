<?php

namespace App\Filament\Resources\Departments\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DepartmentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                ImageEntry::make('image_path')
                    ->label('Imagen')
                    ->disk('public')
                    ->square(),

                TextEntry::make('name')
                    ->label('Nombre')
                    ->placeholder('-'),

                TextEntry::make('description')
                    ->label('Descripción')
                    ->placeholder('-')
                    ->columnSpanFull(),

                IconEntry::make('show_card')
                    ->label('Vista de tarjeta')
                    ->boolean(),

                TextEntry::make('updated_at')
                    ->label('Ultima actualización')
                    ->dateTime()
                    ->placeholder('-'),
                    
            ]);
    }
}

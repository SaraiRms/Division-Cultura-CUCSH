<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('Nombre'),

                TextEntry::make('email')
                    ->label('Correo electrónico'),

                IconEntry::make('is_active')
                    ->label('Activo')
                    ->boolean(),

                TextEntry::make('roles.name')
                    ->label('Rol')
                    ->badge(),

                TextEntry::make('created_at')
                    ->label('Creado el')
                    ->dateTime()
                    ->placeholder('-'),

                TextEntry::make('updated_at')
                    ->label('última modificación')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}

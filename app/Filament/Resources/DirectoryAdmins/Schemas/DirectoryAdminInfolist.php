<?php

namespace App\Filament\Resources\DirectoryAdmins\Schemas;

use App\Models\DirectoryAdmin;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;
use Filament\Infolists\Components\ImageEntry;

class DirectoryAdminInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextEntry::make('full_name')
                    ->label('Nombre Completo')
                    ->placeholder('-'),

                TextEntry::make('cargo')
                    ->placeholder('-'),
                
                TextEntry::make('email')
                    ->label('Correo electrónico')
                    ->placeholder('-'),
                
                TextEntry::make('phone')
                    ->label('Teléfono')
                    ->placeholder('-'),
                
                TextEntry::make('phone_ext')
                    ->label('Extensión')
                    ->placeholder('-'),

                TextEntry::make('materias')
                    ->placeholder('-')
                    ->columnSpanFull(),

                TextEntry::make('cv_summary')
                    ->label('Resumen del CV')
                    ->placeholder('-')
                    ->columnSpanFull(),

                TextEntry::make('cv_path')
                    ->label('CV')
                    ->formatStateUsing(fn () => 'Abrir CV en PDF')
                    ->url(fn ($record) => Storage::disk('public')->url($record->cv_path))
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => filled($record->cv_path)),

                ImageEntry::make('photo_path')
                    ->label('Imagen')
                    ->disk('public')
                    ->circular(),

                TextEntry::make('created_by')
                    ->label('Creado por')
                    ->numeric()
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
                    ->label('Eliminado el')
                    ->dateTime()
                    ->visible(fn (DirectoryAdmin $record): bool => $record->trashed()),
        
            ]);
    }
}

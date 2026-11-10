<?php

namespace App\Filament\Resources\DirectoryRectorias\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Section;

class DirectoryRectoriaForm
{
    public static function configure(Schema $schema): Schema
    {
       
    return $schema->
        components([

            Section::make('Datos de la persona')
                ->columnSpanFull()
                ->columns(2)
                ->schema([
            
                    TextInput::make('full_name')
                        ->label('Nombre completo')
                        ->required()
                        ->maxLength(150)
                        ->columnSpanFull(),

                    TextInput::make('cargo')
                        ->label('Cargo')
                        ->maxLength(150),

                    TextInput::make('area')
                        ->label('Área')
                        ->maxLength(150),

                    FileUpload::make('photo_path')
                        ->label('Fotografía')
                        ->image()
                        ->disk('public')
                        ->directory('directory_rectoria')
                        ->visibility('public')
                        ->maxSize(5120)
                        ->columnSpanFull(),

                    Hidden::make('created_by')
                        ->default(fn () => auth()->id()),

                ]),
        ]);

    }
}

<?php

namespace App\Filament\Resources\Graduates\Schemas;

use App\Models\Graduate;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class GraduateForm
{
    public static function configure(Schema $schema): Schema
    {
        
        return $schema->components([
            Section::make('Egresados')
                ->columnSpanFull()
                ->schema([
                    Select::make('program_id')
                        ->label('Programa')
                        ->relationship('program', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),

                    TextInput::make('title')
                        ->label('Título')
                        ->placeholder('Ej. Generación 2020-2024')
                        ->maxLength(200),

                    FileUpload::make('image_path')
                        ->label('Imagen de los egresados')
                        ->image()
                        ->disk('public')
                        ->directory('graduates')
                        ->visibility('public')
                        ->maxSize(5120)
                        ->required(),

                    Select::make('status')
                        ->label('Estado')
                        ->options(fn () => auth()->user()?->can('publicar_contenido')
                            ? Graduate::STATUS_LABELS
                            : ['pending' => Graduate::STATUS_LABELS['pending']])
                        ->default('pending')
                        ->disabled(fn () => ! auth()->user()?->can('publicar_contenido'))
                        ->dehydrated()
                        ->required(),

                    Hidden::make('uploaded_by')
                        ->default(fn () => auth()->id()),

                ]),

        ]);


    }
}

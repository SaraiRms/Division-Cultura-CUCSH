<?php

namespace App\Filament\Resources\Podcasts\Schemas;

use App\Models\Podcast;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;


class PodcastForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Episodio')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([

                        TextInput::make('title')
                            ->label('Titulo del Episodio')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        TextInput::make('video_url')
                            ->label('Enlace del video')
                            ->url()
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Textarea::make('description')
                            ->label('Descripción del Episodio')
                            ->rows(5)
                            ->columnSpanFull(),

                        FileUpload::make('image_path')
                            ->label('Imagen de la tarjeta')
                            ->image()
                            ->disk('public')
                            ->directory('podcasts')
                            ->visibility('public')
                            ->maxSize(5120),

                        Select::make('status')
                            ->label('Estado')
                            ->options(Podcast::STATUS_LABELS)
                            ->default('draft')
                            ->required()
                            ->helperText('Solo los episodios publicados aparecen en la página'),

                        Hidden::make('created_by')
                            ->default(fn () => auth()->id()),

                    ])
                
            

            ]);
    }
}

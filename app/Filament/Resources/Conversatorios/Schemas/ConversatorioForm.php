<?php

namespace App\Filament\Resources\Conversatorios\Schemas;

use App\Models\Conversatorio;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Schema;

class ConversatorioForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Información del conversatorio')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        
                        TextInput::make('title')
                            ->label('Título')
                            ->required()
                            ->maxLength(200)
                            ->columnSpanFull(),

                        TextInput::make('speaker')
                            ->label('Ponente(s)')
                            ->maxLength(200),

                        DateTimePicker::make('scheduled_at')
                            ->label('Fecha y hora')
                            ->seconds(false)
                            ->native(false)
                            ->displayFormat('d/m/Y H:i')
                            ->required(),

                        TextInput::make('stream_url')
                            ->label('Enlace de la transmisión (YouTube)')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://www.youtube.com/watch?v=...')
                            ->columnSpanFull(),

                        Select::make('status')
                            ->label('Estado')
                            ->options(Conversatorio::STATUS_LABELS)
                            ->default('scheduled')
                            ->required()
                            ->helperText('"En vivo" se muestra en grande en la página. "Finalizado" pasa a Conversatorios anteriores.'),

                        Textarea::make('description')
                            ->label('Descripción')
                            ->rows(4)
                            ->columnSpanFull(),

                        Hidden::make('created_by')
                            ->default(fn () => auth()->id()),
                    ]),

            ]);
    }
}

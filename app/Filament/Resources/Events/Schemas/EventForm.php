<?php

namespace App\Filament\Resources\Events\Schemas;

use App\Models\Event;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class EventForm
{

    protected static function canReview(): bool
    {
        return (bool) auth()->user()?->can('revisar_contenido');
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            
                TextInput::make('title')
                    ->label('Titulo del evento')
                    ->required()
                    ->maxLength(200),

                RichEditor::make('description')
                    ->label('Descripción del evento')
                    ->placeholder('Incluye información relevante sobre el evento')
                    ->required()
                    ->columnSpanFull(),

                Select::make('status')
                    ->label('Estado')
                    ->options(fn () => static::canReview()
                     ? Event::STATUS_LABELS
                      : ['draft' => Event::STATUS_LABELS['draft']])
                    ->default('draft')
                    ->disabled(fn () => ! static::canReview())
                    ->dehydrated()
                    ->required(),

                    Hidden::make('created_by')
                    ->default(fn () => auth()->id()),

                TextInput::make('location')
                    ->label('Ubicación del evento')
                    ->placeholder('Ejemplo: Auditorio de la Facultad de Ciencias Sociales')
                    ->required()
                    ->maxLength(200),

                DateTimePicker::make('starts_at')
                    ->label('Fecha y hora de inicio del evento')
                    ->seconds(false)
                    ->native(false)
                    ->displayFormat('d/m/Y H:i')
                    ->required(),

                FileUpload::make('image')
                    ->label('Imagen del evento')
                    ->image()
                    ->disk('public')
                    ->directory('events')
                    ->visibility('public')
                    ->maxSize(5120)
                    ->required(),
            
        ]);
    }
}

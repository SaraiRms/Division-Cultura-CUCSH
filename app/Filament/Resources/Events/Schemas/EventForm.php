<?php

namespace App\Filament\Resources\Events\Schemas;

use App\Models\Event;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Model;

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

                FileUpload::make('image_path')
                    ->label('Imagen principal')
                    ->image()
                    ->imageEditor()
                    ->imageEditorAspectRatios(['16:9'])
                    ->imageCropAspectRatio('16:9')
                    ->imageResizeTargetWidth('1280')
                    ->imageResizeTargetHeight('720')
                    ->disk('public')
                    ->directory('events')
                    ->visibility('public')
                    ->maxSize(5120)
                    ->helperText('Se recorta en formato horizontal 16:9. Usa una imagen horizontal para mejores resultados.')
                    ->required(),

                RichEditor::make('description')
                    ->label('Descripción del evento')
                    ->placeholder('Incluye información relevante sobre el evento')
                    ->required()
                    ->columnSpanFull(),

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

                Select::make('status')
                    ->label('Estado')
                    ->options(fn () => static::canReview()
                     ? Event::STATUS_LABELS
                      : ['draft' => Event::STATUS_LABELS['draft']])
                    ->default('draft')
                    ->disabled(fn () => ! static::canReview())
                    ->dehydrated()
                    ->required(),

                Toggle::make('is_featured')
                    ->label('Destacar en el banner de Inicio')
                    ->helperText('Solo para eventos grandes. Máximo 3 a la vez. Necesita imagen.')
                    ->visible(fn () => (bool) auth()->user()?->can('publicar_contenido'))
                    ->rule(fn (?Model $record) => function (string $attribute, $value, \Closure $fail) use ($record) {
                        if (! $value) {
                            return;
                        }
                        $otros = Event::where('is_featured', true)
                            ->when($record, fn ($q) => $q->whereKeyNot($record->getKey()))
                            ->count();

                        if ($otros >= 3) {
                            $fail('Ya hay 3 eventos destacados. Quita uno antes de destacar otro.');
                        }
                    }),
            
        ]);
    }
}

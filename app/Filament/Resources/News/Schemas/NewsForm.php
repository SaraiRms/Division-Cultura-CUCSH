<?php

namespace App\Filament\Resources\News\Schemas;

use App\Models\News;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

class NewsForm
{
    // ¿Puede publicar? (según la matriz de permisos)
    protected static function canPublish(): bool
    {
        return (bool) auth()->user()?->can('publicar_contenido');
    }

    // ¿Puede revisar y devolver? (cambiar el estado de lo que otros enviaron)
    protected static function canReview(): bool
    {
        return (bool) auth()->user()?->can('revisar_contenido');
    }

    // Estados que puede elegir cada persona.
    protected static function statusOptions(): array
    {
        if (static::canPublish()) {
            return News::STATUS_LABELS;
        }

        if (static::canReview()) {
            return array_intersect_key(News::STATUS_LABELS, array_flip(['draft', 'pending', 'rejected']));
        }

        // Auxiliar académico: lo que crea siempre queda pendiente de revisión.
        return ['pending' => News::STATUS_LABELS['pending']];
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Contenido de la noticia')
                ->columnSpanFull()
                ->schema([
                    TextInput::make('title')
                        ->label('Título')
                        ->required()
                        ->maxLength(200),

                    TextInput::make('slug')
                        ->label('Dirección (slug)')
                        ->helperText('Déjalo vacío: se genera solo a partir del título.')
                        ->maxLength(220)
                        ->unique(ignoreRecord: true),

                    Textarea::make('summary')
                        ->label('Resumen corto')
                        ->maxLength(500)
                        ->rows(3),

                    RichEditor::make('body')
                        ->label('Texto de la noticia')
                        ->helperText('Aquí puedes poner enlaces, negritas e imágenes adicionales.')
                        ->fileAttachmentsDisk('public')
                        ->fileAttachmentsDirectory('news/body')
                        ->columnSpanFull(),

                    FileUpload::make('image_path')
                        ->label('Imagen principal')
                        ->image()
                        ->disk('public')
                        ->directory('news')
                        ->visibility('public')
                        ->maxSize(4096), // 4 MB
                ]),

            Section::make('Publicación')
                ->columnSpanFull()
                ->schema([
                    Select::make('status')
                        ->label('Estado')
                        ->options(fn () => static::statusOptions())
                        ->default(fn () => static::canReview() ? 'draft' : 'pending')
                        // El auxiliar académico no puede cambiarlo: su noticia queda pendiente de revisión.
                        ->disabled(fn () => ! static::canReview())
                        ->dehydrated()
                        ->required()
                        ->helperText('Al guardarla, el editor o el responsable institucional la revisan para publicarla o devolverla.'),

                    Toggle::make('is_featured')
                        ->label('Destacada (carrusel del inicio)')
                        ->visible(fn () => static::canPublish())
                        // Máximo 4 destacadas a la vez!!.
                        ->rule(fn (?Model $record) => function (string $attribute, $value, \Closure $fail) use ($record) {
                            if (! $value) {
                                return;
                            }

                            $otras = News::where('is_featured', true)
                                ->when($record, fn ($q) => $q->whereKeyNot($record->getKey()))
                                ->count();

                            if ($otras >= 4) {
                                $fail('Ya hay 4 noticias destacadas. Quita una antes de destacar otra.');
                            }
                        }),

                    DateTimePicker::make('published_at')
                        ->label('Fecha de publicación')
                        ->helperText('Déjala vacía y se pone la fecha de hoy al publicar.')
                        ->visible(fn () => static::canPublish()),

                    // La persona que crea la noticia queda como autora automáticamente.
                    Hidden::make('author_id')->default(fn () => auth()->id()),
                ]),
        ]);
    }
}

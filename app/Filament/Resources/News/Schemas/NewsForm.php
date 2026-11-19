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
use App\Models\Graduate;

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
    protected static function statusOptions(?Model $record = null): array
    {
        if (static::canPublish()) {
            $permitidos = ['draft', 'published'];
            // Quien publica elige entre dejarla en borrador o publicarla.
            // El estado actual también se muestra, para que el campo no salga vacío.

            if ($record) {
                $permitidos[] = $record->status;
            }
        } else {
            // El auxiliar elige entre seguir editando (borrador) o enviarla a revisión.
            $permitidos = ['draft', 'pending'];
        }

        return array_intersect_key(News::STATUS_LABELS, array_flip(array_unique($permitidos)));
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
                        ->label('Enlace')
                        ->helperText('Déjalo vacío: se genera solo a partir del título.')
                        ->maxLength(220)
                        ->unique(ignoreRecord: true),

                    Textarea::make('summary')
                        ->label('Resumen corto')
                        ->maxLength(500)
                        ->rows(3),

                    Section::make('Corrección solicitada')
                        ->columnSpanFull()
                        ->visible(fn (?Model $record) => $record?->status === 'rejected' && filled($record?->review_note))
                        ->schema([
                            Textarea::make('review_note')
                                ->label('Observaciones de quien revisó')
                                ->disabled()
                                ->dehydrated(false)
                                ->rows(3),

                            TextInput::make('reviewer_name')
                                ->label('Devuelta por')
                                ->formatStateUsing(fn (?Model $record) => $record?->reviewer?->name)
                                ->disabled()
                                ->dehydrated(false),
                        ]),

                    RichEditor::make('body')
                        ->label('Contenido de la noticia')
                        ->helperText('Aquí puedes poner enlaces, negritas e imágenes adicionales.')
                        ->fileAttachmentsDisk('public')
                        ->fileAttachmentsDirectory('news/body')
                        ->columnSpanFull(),

                    FileUpload::make('image_path')
                        ->label('Imagen principal')
                        ->image()
                        ->imageEditor()
                        ->imageEditorAspectRatios(['16:9'])
                        ->imageCropAspectRatio('16:9')
                        ->imageResizeTargetWidth('1280')
                        ->imageResizeTargetHeight('720')
                        ->disk('public')
                        ->directory('news')
                        ->visibility('public')
                        ->maxSize(5120)
                        ->helperText('Se recorta en formato horizontal 16:9. Usa una imagen horizontal para mejores resultados.')
                        ->required(),
                ]),
 
                Section::make('Publicación')
                    ->columnSpanFull()
                    ->schema([
                        Select::make('status')
                            ->label('Estado')
                            ->options(function (?Model $record) {
                                $puedePublicar = auth()->user()?->can('publicar_contenido');

                                // Quien publica solo necesita "Publicado"; el auxiliar, "Pendiente de autorizar".
                                $permitidos = $puedePublicar ? ['published'] : ['pending'];

                                if ($puedePublicar && $record) {
                                    $permitidos[] = $record->status; // para que se vea el estado actual
                                }

                                return array_intersect_key(Graduate::STATUS_LABELS, array_flip(array_unique($permitidos)));
                            })
                            ->default(fn () => auth()->user()?->can('publicar_contenido') ? 'published' : 'pending')
                            ->required(),

                        Toggle::make('is_featured')
                            ->label('Destacada (carrusel del inicio)')
                            ->visible(fn () => static::canPublish())
                            // Máximo 4 destacadas a la vez.
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
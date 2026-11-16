<?php

namespace App\Filament\Resources\Graduates\Schemas;

use App\Models\Graduate;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Illuminate\Database\Eloquent\Model;

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
                        ->label('Imagen')
                        ->image()
                        ->disk('public')
                        ->directory('graduates')
                        ->visibility('public')
                        ->maxSize(5120)
                        ->required(),

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

                    Hidden::make('uploaded_by')
                        ->label('Actualizado por')
                        ->default(fn () => auth()->id()),

                ]),

        ]);


    }
}

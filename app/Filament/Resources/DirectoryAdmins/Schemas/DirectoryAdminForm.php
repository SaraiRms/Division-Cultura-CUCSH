<?php

namespace App\Filament\Resources\DirectoryAdmins\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Schema;
use App\Models\DirectoryAdmin;

class DirectoryAdminForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
            // Sección de información personal del administrador
            Section::make('Datos del Administrador')
                ->columnSpanFull()
                ->columns(2)
                ->schema([

                    TextInput::make('full_name')
                        ->label('Nombre Completo')
                        ->required()
                        ->maxLength(150),

                    TextInput::make('email')
                        ->label('Correo electrónico')
                        ->email()
                        ->required()
                        ->maxLength(150),

                    TextInput::make('phone')
                        ->label('Teléfono')
                        ->tel()
                        ->required()
                        ->maxLength(10)
                        ->telRegex('/\A[0-9]{10}\z/'),

                    TextInput::make('phone_ext')
                        ->label('Extensión')
                        ->maxLength(5)
                        ->telRegex('/\A[0-9]{10}\z/'),

                    FileUpload::make('photo_path')
                        ->label('Foto')
                        ->image()
                        ->disk('public')
                        ->directory('directory_admins/photos')
                        ->visibility('public')
                        ->maxSize(5120),
                ]),
                // Sección de información profesional del administrador
                Section::make('Cargo y Departamento(s)')
                ->columnSpanFull()
                ->columns(2)
                ->schema([
                    Select::make('jerarquia')
                        ->label('Cargo')
                        ->options(DirectoryAdmin::NIVELES)
                        ->default(6)
                        ->required()
                        ->native(false)
                        ->helperText('Elige el cargo. Con esto también se ordena el directorio público: 
                            primero la dirección, luego jefaturas, secretaría, coordinaciones, 
                            asistentes y personal académico.'),

                    Select::make('departments')
                        ->label('Departamento(s)')
                        ->relationship('departments', 'name')
                        ->multiple()
                        ->preload()
                        ->helperText('Puedes elegir más de uno si la persona está en varios departamentos.'),
                
                ]),
                // Sección de información académica del administrador
                Section::make('Perfil académico')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([

                    TextInput::make('nivel_academico')
                        ->label('Nivel académico')
                        ->maxLength(100),

                    TextInput::make('area_conocimiento')
                        ->label('Área de conocimiento')
                        ->maxLength(150),

                    Textarea::make('materias')
                        ->label('Materias que imparte')
                        ->rows(3)
                        ->columnSpanFull(),

                    Textarea::make('cv_summary')
                        ->label('Resumen del CV')
                        ->rows(4)
                        ->columnSpanFull(),

                    FileUpload::make('cv_path')
                        ->label('CV en PDF')
                        ->disk('public')
                        ->directory('directory/cv')
                        ->visibility('public')
                        ->acceptedFileTypes(['application/pdf'])
                        ->maxSize(5120)
                        ->columnSpanFull(),

                ]),
                    // Guarda automáticamente quién registró a la persona.
                     Hidden::make('created_by')->default(fn () => auth()->id()),
            ]);
    }
}

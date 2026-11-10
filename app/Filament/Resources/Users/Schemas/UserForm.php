<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use function Laravel\Prompts\select;

class UserForm
{

    public const ROLE_LABELS = [
        'admin_tecnico' => 'Administrador técnico',
        'responsable_institucional' => 'Responsable institucional',
        'editor' => 'Editor o coordinador editorial',
        'auxiliar_academico' => 'Auxiliar académico',
    ];
    
    public static function configure(Schema $schema): Schema
    {

        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(150),

                TextInput::make('email')
                    ->label('Correo electrónico')
                    ->email()
                    ->required()
                    ->maxLength(150)
                    ->unique(ignoreRecord: true),    

                TextInput::make('password')
                    ->label('Contraseña')
                    ->password()
                    ->revealable()
                    ->minLength(8)
                    ->required(fn (string $operation) => $operation === 'create')
                    ->dehydrated(fn (?string $state) => filled($state))
                    ->helperText('La contraseña debe tener al menos 8 caracteres.'),

                Toggle::make('is_active')
                    ->label('Activo')
                    ->default(true),

                Select::make('roles')
                    ->label('Rol')
                    ->relationship('roles', 'name')
                    ->getOptionLabelFromRecordUsing(fn ($record) => self::ROLE_LABELS[$record->name] ?? $record->name)
                    ->multiple()
                    ->preload()
                    ->required(),

            ]);
    }
}

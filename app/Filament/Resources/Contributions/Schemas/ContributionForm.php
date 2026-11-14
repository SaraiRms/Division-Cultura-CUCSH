<?php

namespace App\Filament\Resources\Contributions\Schemas;

use App\Models\Contribution;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class ContributionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Datos recibidos')
                    ->description('Lo que envió la persona. No se puede modificar.')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        Select::make('author_type')
                            ->label('Tipo')
                            ->options(Contribution::TYPE_LABELS)
                            ->disabled(),

                        TextInput::make('contact_email')
                            ->label('Correo de contacto')
                            ->disabled(),

                        Textarea::make('comentarios')
                            ->label('Comentarios')
                            ->rows(3)
                            ->disabled()
                            ->columnSpanFull(),
                    ]),

                Section::make('Publicación')
                    ->description('Para publicar hacen falta un título y un banner.')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        Select::make('status')
                            ->label('Estado')
                            ->options(fn () => self::statusOptions())
                            ->required()
                            ->live(),

                        TextInput::make('title')
                            ->label('Título')
                            ->maxLength(200)
                            ->required(fn (Get $get): bool => $get('status') === 'published'),

                        FileUpload::make('banner_path')
                            ->label('Banner')
                            ->image()
                            ->disk('public')
                            ->directory('aportaciones/banners')
                            ->maxSize(5120)
                            ->required(fn (Get $get): bool => $get('status') === 'published')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    // Quien solo revisa no puede publicar
    private static function statusOptions(): array
    {
        $todas = Contribution::STATUS_LABELS;

        if (auth()->user()?->can('publicar_contenido')) {
            return $todas;
        }

        return collect($todas)->except('published')->all();
    }
}
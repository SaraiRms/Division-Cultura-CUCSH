<?php

namespace App\Filament\Resources\Departments\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class DepartmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Departamentos')
                    ->columnSpanFull()
                    ->schema([

                        TextInput::make('name')
                            ->label('Nombre')
                            ->disabled()
                            ->dehydrated(false),

                        Textarea::make('description')
                            ->label('Descripción')
                            ->rows(6)
                            ->helperText('Breve descripción del departamento'),

                        FileUpload::make('image_path')
                            ->label('Imagen')
                            ->image()
                            ->disk('public')
                            ->directory('departments/images')
                            ->visibility('public')
                            ->maxSize(5120),

                        Hidden::make('updated_by')
                        ->default(fn () => auth()->id())
                        ->dehydrateStateUsing(fn () => auth()->id()),

                    ]),

            ]);
    }
}

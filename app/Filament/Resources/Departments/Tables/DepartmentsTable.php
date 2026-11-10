<?php

namespace App\Filament\Resources\Departments\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DepartmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                
                ImageColumn::make('image_path')
                    ->label('Imagen')
                    ->disk('public')
                    ->square(),

                TextColumn::make('name')
                    ->label('Departamento')
                    ->searchable()
                    ->wrap(),
            
                TextColumn::make('description')
                    ->label('Descripción')
                    ->limit(250)
                    ->placeholder('Pendiente de descripción'),
            
                TextColumn::make('updatedBy.name')
                    ->label('Actualizado por')
                    ->placeholder('-'),

                TextColumn::make('updated_at')
                    ->label('Última actualización')
                    ->dateTime('d/m/y H:i'),

            ])

            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                
            ]);
    }
}

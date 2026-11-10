<?php

namespace App\Filament\Resources\DirectoryAdmins\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DirectoryAdminsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

            ImageColumn::make('photo_path')
                ->label('Foto')
                ->disk('public')
                ->circular(),

            TextColumn::make('full_name')
                ->label('Nombre')
                ->searchable()
                ->sortable(),

            TextColumn::make('cargo')
                ->label('Cargo')
                ->searchable(),

            TextColumn::make('departments.name')
                ->label('Departamento(s)')
                ->badge(),

            TextColumn::make('email')
                ->label('Correo')
                ->searchable()
                ->toggleable(),

            TextColumn::make('phone_ext')
                ->label('Ext.')
                ->toggleable(),
        ])

        ->defaultSort('full_name')
        ->filters([
        
            SelectFilter::make('departments')
                ->label('Departamento')
                ->relationship('departments', 'name')
                ->multiple()
                ->preload(),

            ])

            ->recordActions([
            
                ViewAction::make(),
                EditAction::make(),

            ]);    
    }
}

<?php

namespace App\Filament\Resources\DirectoryAdmins\Tables;

use App\Models\DirectoryAdmin;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use App\Filament\Support\Eliminacion;

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
                ->weight(FontWeight::Medium)
                ->tooltip(fn($record) => $record->full_name)
                ->description(fn($record) => $record->cargo)
                ->searchable(['full_name', 'cargo'])
                ->sortable(),

            TextColumn::make('departments.name')
                ->label('Departamento(s)')
                ->badge()
                ->wrap(),

            TextColumn::make('email')
                ->label('Correo')
                ->searchable()
                ->toggleable(),

            TextColumn::make('phone_ext')
                ->label('Ext.')
                ->toggleable(),
        ])

        ->defaultSort(fn ($query) => $query->orderBy('jerarquia')->orderBy('full_name'))
        ->filters([
            Eliminacion::filtroPapelera(DirectoryAdmin::class),
            SelectFilter::make('departments')
                ->label('Departamento')
                ->relationship('departments', 'name')
                ->multiple()
                ->preload(),

            ])

            ->recordActions([
                Eliminacion::grupo('el', 'maestro', 'full_name'),
            ])
            
            ->toolbarActions([
                BulkActionGroup::make([
                    Eliminacion::eliminarVarios(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);
    }
}

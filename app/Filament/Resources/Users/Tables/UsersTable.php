<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use APP\Filament\Resources\Users\Schemas\UserForm;


class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Correo')
                    ->searchable(),

                TextColumn::make('roles.name')
                    ->label('Rol')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => UserForm::ROLE_LABELS[$state] ?? $state),

                IconColumn::make('is_active')
                    ->label('Activa')
                    ->boolean(),
            ])
                ->defaultSort('name')

            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ]);
    }
} 

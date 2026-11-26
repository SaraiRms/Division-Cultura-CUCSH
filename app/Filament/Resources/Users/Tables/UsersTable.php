<?php

namespace App\Filament\Resources\Users\Tables;

use App\Filament\Resources\Users\Schemas\UserForm;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

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
                ActionGroup::make([
                ViewAction::make(),
                EditAction::make(),
                // Quitar o devolver el acceso sin borrar la cuenta ni su historial
                Action::make('alternar_activo')
                    ->label(fn (User $record) => $record->is_active ? 'Desactivar' : 'Activar')
                    ->icon(fn (User $record) => $record->is_active ? Heroicon::OutlinedNoSymbol : Heroicon::OutlinedCheckCircle)
                    ->color(fn (User $record) => $record->is_active ? 'danger' : 'success')
                    ->requiresConfirmation()
                    ->modalHeading(fn (User $record) => $record->is_active ? 'Desactivar cuenta' : 'Activar cuenta')
                    ->modalDescription(fn (User $record) => $record->is_active
                        ? 'La persona ya no podrá entrar al panel. Lo que creó se conserva.'
                        : 'La persona podrá volver a entrar al panel.')
                    ->authorize('toggleActive')
                    // No se puede desactivar la propia cuenta ni al último administrador de su tipo
                    ->hidden(fn (User $record) => $record->is(auth()->user())
                        || ($record->is_active && $record->esUltimoAdministrador()))
                    ->action(function (User $record) {
                        $record->update(['is_active' => ! $record->is_active]);
        
                        Notification::make()
                            ->title($record->is_active ? 'Cuenta activada' : 'Cuenta desactivada')
                            ->success()
                            ->send();
                    }),
                ])
            ]);
    }
}
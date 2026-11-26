<?php

namespace App\Filament\Resources\Conversatorios\Tables;

use App\Models\Conversatorio;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use App\Filament\Support\Eliminacion;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\BulkActionGroup;

class ConversatoriosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Título')
                    ->weight(FontWeight::Medium)
                    ->tooltip(fn($record) => $record->title)
                    ->description(fn($record) => $record->speaker)
                    ->searchable(['title', 'speaker'])
                    ->limit(100),

                TextColumn::make('scheduled_at')
                    ->label('Fecha y hora')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => Conversatorio::STATUS_LABELS[$state] ?? $state)
                    ->color(fn (string $state) => match ($state) {
                        'scheduleb' => 'warning',
                        'live' => 'success',
                        'finished' => 'danger',
                        default => 'gray'
                    }),

            ])

            ->defaultSort('scheduled_at', 'desc')
            ->filters([
                Eliminacion::filtroPapelera(Conversatorio::class),
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options(Conversatorio::STATUS_LABELS),
            ])
                    
            ->recordActions([
                ActionGroup::make([
                    EditAction::make()
                        ->label('Editar')
                        ->icon(Heroicon::Eye),
                ]),

                    // Terminar recordActions

            ViewAction::make(),
                EditAction::make()->hidden(fn (Conversatorio $record) => $record->trashed()),
                Eliminacion::eliminar('el', 'conversatorio'),
                Eliminacion::restaurar(),
                Eliminacion::eliminarDefinitivo(),
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

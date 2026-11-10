<?php

namespace App\Filament\Resources\Conversatorios\Tables;

use App\Models\Conversatorio;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ConversatoriosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->limit(100),

                TextColumn::make('speaker')
                    ->label('Ponente(s)')
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
                        'live' => 'success',
                        'scheduleb' => 'info',
                        default => 'gray'
                    }),

            ])
                    ->defaultSort('scheduled_at', 'desc')
                    ->filters([

                        SelectFilter::make('status')
                            ->label('Estado')
                            ->options(Conversatorio::STATUS_LABELS),
                            
                    ]);
    }
}

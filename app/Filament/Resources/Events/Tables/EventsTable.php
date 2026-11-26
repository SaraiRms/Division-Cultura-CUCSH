<?php

namespace App\Filament\Resources\Events\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Models\Event;
use App\Filament\Support\Eliminacion;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class EventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('title')
                ->label('Evento')
                ->searchable(['title', 'summary', 'body'])
                ->limit(50),

            TextColumn::make('starts_at')
                ->label('Fecha y Hora')
                ->dateTime('d/m/Y H:i')
                ->sortable(),
                
            TextColumn::make('status')
                ->label('Estado')
                ->badge()
                ->formatStateUsing(fn (string $state) => Event::STATUS_LABELS[$state] ?? $state)
                ->color(fn (string $state) => match ($state) {
                    'published' => 'success',
                    'cancelled' => 'danger',
                    default => 'gray',
                }),

            TextColumn::make('creator.name')
                ->label('Creado por')    
            ])
            ->filters([
                Eliminacion::filtroPapelera(Event::class),
                Filter::make('fecha')
                    ->label('Fecha de publicación')
                    ->schema([
                        DatePicker::make('desde')->label('Desde'),
                        DatePicker::make('hasta')->label('Hasta'),
                    ])
                    ->query(fn (Builder $query, array $data) => $query
                        ->when($data['desde'] ?? null, fn ($q, $d) => $q->whereDate('published_at', '>=', $d))
                        ->when($data['hasta'] ?? null, fn ($q, $d) => $q->whereDate('published_at', '<=', $d))),
            ])  
            ->recordActions([
                Eliminacion::grupo('el', 'evento'),
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

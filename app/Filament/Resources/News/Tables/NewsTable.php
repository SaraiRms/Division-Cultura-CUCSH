<?php

namespace App\Filament\Resources\News\Tables;

use App\Models\News;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Filament\Forms\Components\Textarea;
use App\Filament\Support\Eliminacion;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;

class NewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->limit(60),

                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => News::STATUS_LABELS[$state] ?? $state)
                    ->color(fn (string $state) => match ($state) {
                        'published' => 'success',
                        'pending' => 'warning',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                
                TextColumn::make('review_note')
                    ->label('Observaciones')
                    ->limit(40)
                    ->tooltip(fn (News $record) => $record->review_note)
                    ->placeholder('—'),

                IconColumn::make('is_featured')
                    ->label('Destacada')
                    ->boolean(),

                TextColumn::make('author.name')
                    ->label('Autor(a)'),

                TextColumn::make('published_at')
                    ->label('Fecha de publicación')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->placeholder('—'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Eliminacion::filtroPapelera(News::class),
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options(News::STATUS_LABELS),

                TernaryFilter::make('is_featured')
                    ->label('Destacadas'),
            ])
            ->recordActions([
                // Atajos para quien revisa: publicar o devolver lo que está pendiente.
                Action::make('publicar')
                    ->label('Publicar')
                    ->icon(Heroicon::OutlinedCheckCircle)
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (News $record) => $record->status === 'pending'
                        && auth()->user()?->can('publicar_contenido'))
                    ->action(function (News $record) {
                        $record->update(['status' => 'published', 'review_note' => null]);

                        Notification::make()->title('Noticia publicada')->success()->send();
                    }),

                Action::make('devolver')
                    ->label('Devolver')
                    ->icon(Heroicon::OutlinedArrowUturnLeft)
                    ->color('danger')
                    ->modalHeading('Devolver noticia')
                    ->modalDescription('Explica qué debe corregir. Quien la creó verá este mensaje.')
                    ->schema([
                        Textarea::make('review_note')
                            ->label('¿Qué hay que corregir?')
                            ->required()
                            ->maxLength(1000),
                    ])
                    ->visible(fn (News $record) => $record->status === 'pending'
                        && auth()->user()?->can('revisar_contenido'))
                    ->action(function (News $record, array $data) {
                        $record->update([
                            'status'      => 'rejected',
                            'review_note' => $data['review_note'],
                            'reviewed_by' => auth()->id(),
                        ]);

                        Notification::make()->title('Noticia devuelta')->warning()->send();
                    }),

                ViewAction::make(),
                EditAction::make()->hidden(fn (News $record) => $record->trashed()),
                Eliminacion::eliminar('la', 'noticia'),
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

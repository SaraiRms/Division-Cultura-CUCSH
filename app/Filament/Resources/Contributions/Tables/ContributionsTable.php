<?php

namespace App\Filament\Resources\Contributions\Tables;

use App\Filament\Support\Eliminacion;
use App\Models\Contribution;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ContributionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('author_type')
                
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => Contribution::TYPE_LABELS[$state] ?? $state)
                    ->color(fn (string $state) => $state === 'alumno' ? 'info' : 'primary'),

                TextColumn::make('title')
                    ->label('Aportación')
                    ->weight(FontWeight::Medium)
                    ->tooltip(fn ($record) => $record->title)
                    ->description(fn ($record) => $record->contact_email)
                    ->searchable(['title', 'contact_email']),

                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => Contribution::STATUS_LABELS[$state] ?? $state)
                    ->color(fn (string $state) => match ($state) {
                        'in_review' => 'warning',
                        'published' => 'success',
                        'rejected'  => 'danger',
                        default     => 'gray',
                    }),

                 TextColumn::make('review_note')
                     ->label('Motivo')
                     ->limit(40)
                     ->tooltip(fn (Contribution $record) => $record->review_note)
                     ->placeholder('—')
                     ->toggleable(),

                TextColumn::make('reviewer.name')
                    ->label('Revisó')
                    ->placeholder('—'),

                TextColumn::make('created_at')
                    ->label('Fecha de recepción')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options(Contribution::STATUS_LABELS),

                SelectFilter::make('author_type')
                    ->label('Tipo')
                    ->options(Contribution::TYPE_LABELS),
            ])

            ->recordActions([
                ActionGroup::make([
                    EditAction::make()
                        ->label('Revisar')
                        ->icon(Heroicon::OutlinedClipboardDocumentCheck),

                    Action::make('ver_pdf')
                        ->label('Ver PDF')
                        ->icon(Heroicon::OutlinedDocumentText)
                        ->url(fn (Contribution $record) => route('aportaciones.pdf', $record))
                        ->openUrlInNewTab(),

                    Eliminacion::eliminar('la', 'aportación', 'title', papelera: false),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->modalHeading('Eliminar las aportaciones seleccionadas')
                        ->modalDescription('Se borrarán DEFINITIVAMENTE, junto con sus PDF. No se podrá recuperar.')
                        ->modalSubmitActionLabel('Sí, eliminar'),
                ]),
            ]);
    }
}
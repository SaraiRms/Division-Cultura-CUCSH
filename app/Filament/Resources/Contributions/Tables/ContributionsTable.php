<?php

namespace App\Filament\Resources\Contributions\Tables;

use App\Models\Contribution;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Textarea;
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

                TextColumn::make('contact_email')
                    ->label('Correo de contacto')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('title')
                    ->label('Título')
                    ->placeholder('Sin título')
                    ->limit(40),

                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => Contribution::STATUS_LABELS[$state] ?? $state)
                    ->color(fn (string $state) => match ($state) {
                        'received'  => 'warning',
                        'in_review' => 'info',
                        'published' => 'success',
                        'rejected'  => 'danger',
                        default     => 'gray',
                    }),

                TextColumn::make('reviewer.name')
                    ->label('Revisó')
                    ->placeholder('—'),

                TextColumn::make('review_note')
                    ->label('Motivo')
                    ->limit(40)
                    ->tooltip(fn (Contribution $record) => $record->review_note)
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Recibida')
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
                Action::make('ver_pdf')
                    ->label('Ver PDF')
                    ->icon(Heroicon::OutlinedDocumentText)
                    ->url(fn (Contribution $record) => route('aportaciones.pdf', $record))
                    ->openUrlInNewTab(),

                Action::make('rechazar')
                    ->label('Rechazar')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->modalHeading('Rechazar aportación')
                    ->modalDescription('Escribe el motivo. Se guarda junto con tu nombre y la fecha.')
                    ->schema([
                        Textarea::make('review_note')
                            ->label('Motivo del rechazo')
                            ->required()
                            ->maxLength(1000),
                    ])
                    ->action(function (Contribution $record, array $data) {
                        $record->update([
                            'status'      => 'rejected',
                            'review_note' => $data['review_note'],
                        ]);

                        Notification::make()
                            ->title('Aportación rechazada')
                            ->success()
                            ->send();

                    }),

                EditAction::make()->label('Revisar'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
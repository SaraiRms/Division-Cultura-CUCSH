<?php

namespace App\Filament\Resources\Contributions\Tables;

use App\Models\Contribution;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use App\Filament\Support\Eliminacion;
use Filament\Actions\ActionGroup;
use PhpParser\Node\Stmt\Label;

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

            ->actions([
                Action::make('revisar')
                    ->label('Revisar')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->color('primary')
                    ->iconButton()
                    ->tooltip('Revisar Contenido'),

                Action::make('ver_pdf')
                    ->label('Ver PDF')
                    ->icon('heroicon-o-document-text')
                    ->color('gray')
                    ->iconButton()
                    ->tooltip('Ver PDF'),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
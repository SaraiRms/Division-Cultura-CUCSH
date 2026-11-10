<?php

namespace App\Filament\Resources\Departments\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProgramsRelationManager extends RelationManager
{
    protected static string $relationship = 'programs';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(150),

                    Select::make('type')
                    ->label('Tipo')
                    ->required()
                    ->options([
                        'licenciatura' => 'Licenciatura',
                        'maestria' => 'Maestría',
                        'doctorado' => 'Doctorado',
                        'centro' => 'Centro de Investigación',
                    ]),

                Textarea::make('description')
                    ->label('Descripción')
                    ->maxLength(500)
                    ->rows(5),

                Hidden::make('updated_by')
                    ->default(fn () => auth()->id())
                    ->dehydrateStateUsing(fn () => auth()->id()),

            ]);
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('type')
                    ->badge(),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('sort_order')
                    ->numeric(),
                TextEntry::make('updated_by')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([

                TextColumn::make('name')
                    ->label('Nombre')
                    ->wrap(),

                TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'licenciatura' => 'Licenciatura',
                        'maestria' => 'Maestría',
                        'doctorado' => 'Doctorado',
                        'centro' => 'Centro de Investigación',
                        default => $state,
                    })
                    
                    ->color(fn (string $state) => match ($state) {
                        'licenciatura' => 'primary',
                        'maestria' => 'success',
                        'doctorado' => 'warning',
                        'centro' => 'info',
                        default => 'gray',
                    }),

                TextColumn::make('description')
                    ->label('Descripción')
                    ->limit(100)
                    ->placeholder('Pendiente'),

            ])

            ->reorderable('sort_order')
            ->defaultSort('sort_order')

            ->headerActions([
                CreateAction::make(),
            ])

            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

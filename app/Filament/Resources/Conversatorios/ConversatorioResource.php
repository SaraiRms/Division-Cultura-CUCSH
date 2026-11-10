<?php

namespace App\Filament\Resources\Conversatorios;

use UnitEnum;
use BackedEnum;
use App\Models\Conversatorio;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\Conversatorios\Pages\CreateConversatorio;
use App\Filament\Resources\Conversatorios\Pages\EditConversatorio;
use App\Filament\Resources\Conversatorios\Pages\ListConversatorios;
use App\Filament\Resources\Conversatorios\Pages\ViewConversatorio;
use App\Filament\Resources\Conversatorios\Schemas\ConversatorioForm;
use App\Filament\Resources\Conversatorios\Schemas\ConversatorioInfolist;
use App\Filament\Resources\Conversatorios\Tables\ConversatoriosTable;


class ConversatorioResource extends Resource
{

    protected static ?string $model = Conversatorio::class;
    protected static ?string $modelLabel = 'conversatorio';
    protected static ?string $pluralModelLabel = 'conversatorios';
    protected static ?string $navigationLabel = 'Conversatorios';
    protected static string|UnitEnum|null $navigationGroup = 'Contenido';
    protected static ?int $navigationSort = 3;

    
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMicrophone;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return ConversatorioForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ConversatorioInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ConversatoriosTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListConversatorios::route('/'),
            'create' => CreateConversatorio::route('/create'),
            'view' => ViewConversatorio::route('/{record}'),
            'edit' => EditConversatorio::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}

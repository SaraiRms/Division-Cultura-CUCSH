<?php

namespace App\Filament\Resources\DirectoryRectorias;

use UnitEnum;
use App\Filament\Resources\DirectoryRectorias\Pages\CreateDirectoryRectoria;
use App\Filament\Resources\DirectoryRectorias\Pages\EditDirectoryRectoria;
use App\Filament\Resources\DirectoryRectorias\Pages\ListDirectoryRectorias;
use App\Filament\Resources\DirectoryRectorias\Pages\ViewDirectoryRectoria;
use App\Filament\Resources\DirectoryRectorias\Schemas\DirectoryRectoriaForm;
use App\Filament\Resources\DirectoryRectorias\Schemas\DirectoryRectoriaInfolist;
use App\Filament\Resources\DirectoryRectorias\Tables\DirectoryRectoriasTable;
use App\Models\DirectoryRectoria;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DirectoryRectoriaResource extends Resource
{
    protected static ?string $model = DirectoryRectoria::class;
    protected static ?string $modelLabel = 'rectoría';
    protected static ?string $pluralModelLabel = 'rectoría';
    protected static ?string $navigationLabel = 'Rectoría';
    protected static string|UnitEnum|null $navigationGroup = 'Institucional';
    protected static ?int $navigationSort = 2;


    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingLibrary;

    protected static ?string $recordTitleAttribute = 'full_name';

    public static function form(Schema $schema): Schema
    {
        return DirectoryRectoriaForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DirectoryRectoriaInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DirectoryRectoriasTable::configure($table);
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
            'index' => ListDirectoryRectorias::route('/'),
            'create' => CreateDirectoryRectoria::route('/create'),
            'view' => ViewDirectoryRectoria::route('/{record}'),
            'edit' => EditDirectoryRectoria::route('/{record}/edit'),
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

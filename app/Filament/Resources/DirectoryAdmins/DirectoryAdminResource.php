<?php

namespace App\Filament\Resources\DirectoryAdmins;

use UnitEnum;
use App\Filament\Resources\DirectoryAdmins\Pages\CreateDirectoryAdmin;
use App\Filament\Resources\DirectoryAdmins\Pages\EditDirectoryAdmin;
use App\Filament\Resources\DirectoryAdmins\Pages\ListDirectoryAdmins;
use App\Filament\Resources\DirectoryAdmins\Pages\ViewDirectoryAdmin;
use App\Filament\Resources\DirectoryAdmins\Schemas\DirectoryAdminForm;
use App\Filament\Resources\DirectoryAdmins\Schemas\DirectoryAdminInfolist;
use App\Filament\Resources\DirectoryAdmins\Tables\DirectoryAdminsTable;
use App\Models\DirectoryAdmin;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DirectoryAdminResource extends Resource
{
    protected static ?string $model = DirectoryAdmin::class;
    protected static ?string $modelLabel = 'Administrativo';
    protected static ?string $pluralModelLabel = 'administrativos';
    protected static ?string $navigationLabel = 'Administrativos';
    protected static string|UnitEnum|null $navigationGroup = 'Institucional';
    protected static ?int $navigationSort = 2;


    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $recordTitleAttribute = 'full_name';

    public static function form(Schema $schema): Schema
    {
        return DirectoryAdminForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DirectoryAdminInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DirectoryAdminsTable::configure($table);
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
            'index' => ListDirectoryAdmins::route('/'),
            'create' => CreateDirectoryAdmin::route('/create'),
            'view' => ViewDirectoryAdmin::route('/{record}'),
            'edit' => EditDirectoryAdmin::route('/{record}/edit'),
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

<?php

namespace App\Filament\Resources\Graduates;

use UnitEnum;
use App\Filament\Resources\Graduates\Pages\CreateGraduate;
use App\Filament\Resources\Graduates\Pages\EditGraduate;
use App\Filament\Resources\Graduates\Pages\ListGraduates;
use App\Filament\Resources\Graduates\Pages\ViewGraduate;
use App\Filament\Resources\Graduates\Schemas\GraduateForm;
use App\Filament\Resources\Graduates\Schemas\GraduateInfolist;
use App\Filament\Resources\Graduates\Tables\GraduatesTable;
use App\Models\Graduate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GraduateResource extends Resource
{
    protected static ?string $model = Graduate::class;
    protected static ?string $modelLabel = 'egresados';
    protected static ?string $pluralModelLabel = 'egresados';
    protected static ?string $navigationLabel = 'Egresados';
    protected static string|UnitEnum|null $navigationGroup = 'Institucional';
    protected static ?int $navigationSort = 4;
    

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return GraduateForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return GraduateInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GraduatesTable::configure($table);
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
            'index' => ListGraduates::route('/'),
            'create' => CreateGraduate::route('/create'),
            'view' => ViewGraduate::route('/{record}'),
            'edit' => EditGraduate::route('/{record}/edit'),
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

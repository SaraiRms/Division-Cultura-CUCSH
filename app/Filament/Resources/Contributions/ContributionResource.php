<?php

namespace App\Filament\Resources\Contributions;

use App\Filament\Resources\Contributions\Pages\CreateContribution;
use App\Filament\Resources\Contributions\Pages\EditContribution;
use App\Filament\Resources\Contributions\Pages\ListContributions;
use App\Filament\Resources\Contributions\Schemas\ContributionForm;
use App\Filament\Resources\Contributions\Tables\ContributionsTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Support\Htmlable;
use App\Models\Contribution;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;


class ContributionResource extends Resource
{

    protected static ?string $modelLabel = 'aportación';
    protected static ?string $pluralModelLabel = 'aportaciones';
    protected static ?string $navigationLabel = 'Aportaciones';
    protected static ?int $navigationSort = 5;
    protected static ?string $model = Contribution::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    // protected static ?string $recordTitleAttribute = 'Contribuciones';


    // No se pueden crear desde el panel
    public static function canCreate(): bool
    {
        return false;
    }

    // Como el título puede estar vacío al inicio, se muestra "Aportación #N"
    public static function getRecordTitle(?Model $record): string|Htmlable|null
    {
        return $record?->title ?: 'Aportación #' . $record?->id;
    }

    // El globito con el número de recibidas
    public static function getNavigationBadge(): ?string
    {
        $pendientes = static::getModel()::where('status', 'received')->count();

        return $pendientes > 0 ? (string) $pendientes : null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'warning';
    }

    ///////////////
    public static function table(Table $table): Table
    {
        return ContributionsTable::configure($table);
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
            'index' => ListContributions::route('/'),
            'create' => CreateContribution::route('/create'),
            'edit' => EditContribution::route('/{record}/edit'),
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return ContributionForm::configure($schema);
    }

}

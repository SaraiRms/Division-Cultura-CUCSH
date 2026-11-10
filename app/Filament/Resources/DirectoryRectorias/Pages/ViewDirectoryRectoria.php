<?php

namespace App\Filament\Resources\DirectoryRectorias\Pages;

use App\Filament\Resources\DirectoryRectorias\DirectoryRectoriaResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDirectoryRectoria extends ViewRecord
{
    protected static string $resource = DirectoryRectoriaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

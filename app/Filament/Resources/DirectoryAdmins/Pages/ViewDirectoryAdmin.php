<?php

namespace App\Filament\Resources\DirectoryAdmins\Pages;

use App\Filament\Resources\DirectoryAdmins\DirectoryAdminResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDirectoryAdmin extends ViewRecord
{
    protected static string $resource = DirectoryAdminResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\DirectoryAdmins\Pages;

use App\Filament\Resources\DirectoryAdmins\DirectoryAdminResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDirectoryAdmins extends ListRecords
{
    protected static string $resource = DirectoryAdminResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

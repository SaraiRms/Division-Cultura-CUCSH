<?php

namespace App\Filament\Resources\DirectoryRectorias\Pages;

use App\Filament\Resources\DirectoryRectorias\DirectoryRectoriaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDirectoryRectorias extends ListRecords
{
    protected static string $resource = DirectoryRectoriaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

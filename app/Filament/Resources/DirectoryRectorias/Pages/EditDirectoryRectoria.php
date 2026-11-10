<?php

namespace App\Filament\Resources\DirectoryRectorias\Pages;

use App\Filament\Resources\DirectoryRectorias\DirectoryRectoriaResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditDirectoryRectoria extends EditRecord
{
    protected static string $resource = DirectoryRectoriaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}

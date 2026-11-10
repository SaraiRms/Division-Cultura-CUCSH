<?php

namespace App\Filament\Resources\DirectoryAdmins\Pages;

use App\Filament\Resources\DirectoryAdmins\DirectoryAdminResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditDirectoryAdmin extends EditRecord
{
    protected static string $resource = DirectoryAdminResource::class;

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

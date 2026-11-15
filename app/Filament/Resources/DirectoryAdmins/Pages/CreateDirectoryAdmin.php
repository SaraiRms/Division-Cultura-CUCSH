<?php

namespace App\Filament\Resources\DirectoryAdmins\Pages;

use App\Filament\Resources\DirectoryAdmins\DirectoryAdminResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions\Action;

class CreateDirectoryAdmin extends CreateRecord
{
    protected static string $resource = DirectoryAdminResource::class;

    public function getTitle(): string
    {
        return 'Registrar Académico';
    }

     protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction() //Botoncito de crear
            ->label('Registrar');
    }

    protected function getCreateAnotherFormAction(): Action
    {
        return parent::getCreateAnotherFormAction()  //Botoncito de crear y crear otro
            ->label('Registrar y registrar otro');
    }
}

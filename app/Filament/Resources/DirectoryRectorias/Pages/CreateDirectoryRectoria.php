<?php

namespace App\Filament\Resources\DirectoryRectorias\Pages;

use App\Filament\Resources\DirectoryRectorias\DirectoryRectoriaResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions\Action;

class CreateDirectoryRectoria extends CreateRecord
{
    protected static string $resource = DirectoryRectoriaResource::class;

    public function getTitle(): string
    {
        return 'Registrar integrante de Rectoría'; // Botoncito para que no diga "crear Rectoría"
    }

    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction() //Botoncito para que no diga: "crear"
            ->label('Registrar');
    }

    protected function getCreateAnotherFormAction(): Action
    {
        return parent::getCreateAnotherFormAction()  //Botoncito para que no diga: "crear y crear otro"
            ->label('Registrar y registrar otro');
    }

}



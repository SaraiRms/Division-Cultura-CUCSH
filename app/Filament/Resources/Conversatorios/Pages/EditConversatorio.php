<?php

namespace App\Filament\Resources\Conversatorios\Pages;

use App\Filament\Resources\Conversatorios\ConversatorioResource;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Support\Eliminacion;

class EditConversatorio extends EditRecord
{
    protected static string $resource = ConversatorioResource::class;

    protected function getHeaderActions(): array
    {
        return [
        Eliminacion::eliminar('el', 'conversatorio'),
        Eliminacion::restaurar(),
        Eliminacion::eliminarDefinitivo(),
        ];
    }
}

<?php

namespace App\Filament\Resources\Conversatorios\Pages;

use App\Filament\Resources\Conversatorios\ConversatorioResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewConversatorio extends ViewRecord
{
    protected static string $resource = ConversatorioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

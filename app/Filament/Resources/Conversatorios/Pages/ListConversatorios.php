<?php

namespace App\Filament\Resources\Conversatorios\Pages;

use App\Filament\Resources\Conversatorios\ConversatorioResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListConversatorios extends ListRecords
{
    protected static string $resource = ConversatorioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\Conversatorios\Pages;

use App\Filament\Resources\Conversatorios\ConversatorioResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditConversatorio extends EditRecord
{
    protected static string $resource = ConversatorioResource::class;

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

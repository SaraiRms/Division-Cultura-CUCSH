<?php

namespace App\Filament\Resources\Contributions\Pages;

use App\Filament\Resources\Contributions\ContributionResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Filament\Resources\Pages\EditRecord;

class EditContribution extends EditRecord
{
    protected static string $resource = ContributionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('ver_pdf')
                ->label('Ver PDF')
                ->icon(Heroicon::OutlinedDocumentText)
                ->url(fn () => route('aportaciones.pdf', $this->record))
                ->openUrlInNewTab(),

            DeleteAction::make(),
        ];
    }

}

<?php

namespace App\Filament\Resources\Contributions\Pages;

use App\Filament\Resources\Contributions\ContributionResource;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Support\Eliminacion;
use App\Mail\AportacionRechazada;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Mail;
use App\Mail\AportacionPublicada;

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

            Eliminacion::eliminar('la', 'aportación', 'title', papelera: false),
        ];
    }

    // Al guardar, si el estado cambió a "Publicada" o "Rechazada", se avisa a quien envió la participación.
    protected function afterSave(): void
    {
        $aportacion = $this->record;

        if (! $aportacion->wasChanged('status')) {
            return;
        }

        $correo = match ($aportacion->status) {
            'published' => new AportacionPublicada($aportacion),
            'rejected'  => new AportacionRechazada($aportacion),
            default     => null,
        };

        if (! $correo) {
            return;
        }

        try {
            Mail::to($aportacion->contact_email)->send($correo);

            Notification::make()
                ->title('Se avisó por correo a ' . $aportacion->contact_email)
                ->success()
                ->send();
        } catch (\Throwable $e) {
            report($e);

            Notification::make()
                ->title('El estado se guardó, pero no se pudo enviar el correo')
                ->body('Revisa la configuración del correo (variables MAIL_* del .env).')
                ->warning()
                ->send();
        }
    }

}

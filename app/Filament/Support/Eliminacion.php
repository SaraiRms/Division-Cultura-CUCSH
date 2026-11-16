<?php

namespace App\Filament\Support;

use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Database\Eloquent\Model;

// Botones de eliminar, restaurar y papelera con advertencias, iguales en todo el panel.
class Eliminacion
{
    // Botón "Eliminar" (sirve en la tabla y en la pantalla de edición).
    public static function eliminar(string $articulo, string $nombre, string $campo = 'title', bool $papelera = true): DeleteAction
    {
        return DeleteAction::make()
            ->label('Eliminar')
            ->modalHeading("Eliminar {$nombre}")
            ->modalDescription(function (Model $record) use ($articulo, $nombre, $campo, $papelera) {
                $titulo = $record->{$campo} ?: 'sin título';
                $texto = "Vas a eliminar {$articulo} {$nombre} «{$titulo}». ";

                if (! $papelera) {
                    return $texto . 'Se borrará DEFINITIVAMENTE y no se podrá recuperar.';
                }

                if (($record->status ?? null) === 'published') {
                    $texto .= 'ATENCIÓN: está publicada y dejará de verse en el sitio de inmediato. ';
                }

                return $texto . 'Se enviará a la papelera y solo el administrador podrá restaurarla.';
            })
            ->modalSubmitActionLabel('Sí, eliminar')
            ->failureNotificationTitle('No se pudo eliminar: tiene datos ligados o es el último administrador.');
    }

    // Eliminar varios a la vez.
    public static function eliminarVarios(): DeleteBulkAction
    {
        return DeleteBulkAction::make()
            ->modalHeading('Eliminar los elementos seleccionados')
            ->modalDescription('Se enviarán a la papelera y lo publicado dejará de verse en el sitio. Solo el administrador podrá restaurarlos.')
            ->modalSubmitActionLabel('Sí, eliminar');
    }

    public static function restaurar(): RestoreAction
    {
        return RestoreAction::make()
            ->label('Restaurar')
            ->modalHeading('Restaurar elemento')
            ->modalDescription('Volverá a la lista con el estado que tenía antes de eliminarse.');
    }

    public static function eliminarDefinitivo(): ForceDeleteAction
    {
        return ForceDeleteAction::make()
            ->label('Eliminar definitivamente')
            ->modalHeading('Eliminar definitivamente')
            ->modalDescription('Esta acción NO se puede deshacer. Se borrará para siempre.')
            ->modalSubmitActionLabel('Sí, eliminar para siempre');
    }

    // Filtro "Papelera": solo lo ve quien puede restaurar (el administrador).
    public static function filtroPapelera(string $modelo): TrashedFilter
    {
        return TrashedFilter::make()
            ->label('Papelera')
            ->visible(fn () => auth()->user()?->can('restoreAny', $modelo));
    }
}
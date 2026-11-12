<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        // El mes viaja en la dirección: /eventos?mes=2026-12
        // Si no viene o está mal escrito, usamos el mes actual.
        try {
            $mes = Carbon::createFromFormat('!Y-m', (string) $request->query('mes'))->startOfMonth();
        } catch (\Throwable $e) {
            $mes = now()->startOfMonth();
        }

        // Los eventos publicados de ese mes
        $eventos = Event::published()
            ->inMonth($mes->year, $mes->month)
            ->orderBy('starts_at')
            ->get();

        // Los agrupamos por día: ['2026-11-20' => [evento, evento], ...]
        $porDia = $eventos->groupBy(fn ($e) => $e->starts_at->format('Y-m-d'));

        // Los cuadritos del calendario: del domingo anterior al día 1
        // hasta el sábado posterior al último día del mes
        $dias = CarbonPeriod::create(
            $mes->copy()->startOfWeek(Carbon::SUNDAY),
            $mes->copy()->endOfMonth()->endOfWeek(Carbon::SATURDAY)
        );

        return view('eventos.index', [
            'mes'       => $mes,
            'eventos'   => $eventos,
            'porDia'    => $porDia,
            'dias'      => $dias,
            'anterior'  => $mes->copy()->subMonth()->format('Y-m'),
            'siguiente' => $mes->copy()->addMonth()->format('Y-m'),
            'proximos'  => Event::upcoming()->limit(5)->get(),
        ]);
    }

    public function show(Event $event)
    {
        abort_unless($event->status === 'published', 404);

        return view('eventos.show', ['evento' => $event]);
    }
}
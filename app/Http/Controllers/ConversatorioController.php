<?php

namespace App\Http\Controllers;

use App\Models\Conversatorio;

class ConversatorioController extends Controller
{
    public function index()
    {
        // El grande: el que está en vivo o el próximo programado.
        // Si no hay ninguno, el último que ya terminó.
        $principal = Conversatorio::featured() ?? Conversatorio::latestFinished(1)->first();

        // Los 3 anteriores, sin repetir el principal
        $anteriores = Conversatorio::latestFinished(4)->get()
            ->reject(fn ($c) => $c->id === $principal?->id)
            ->take(3);

        return view('conversatorios.index', compact('principal', 'anteriores'));
    }
}

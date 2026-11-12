<?php

namespace App\Http\Controllers;

use App\Models\Conversatorio;
use App\Models\Event;
use App\Models\News;
use App\Models\Contribution;

class HomeController extends Controller
{

public function index()
{
    return view('home', [
        'destacadas' => News::hero()->get(),
        'recientes'  => News::published()->latest('published_at')->limit(3)->get(),

        // Los más recientes primero; los próximos o en vivo quedan al inicio
        'conversatorios' => Conversatorio::orderByDesc('scheduled_at')->limit(12)->get(),

        // Banners de aportaciones ya publicadas (con banner subido por el administrador)
        'aportacionesAlumnos' => Contribution::published()->ofType('alumno')
            ->whereNotNull('banner_path')->latest()->limit(12)->get(),
        'aportacionesProfesores' => Contribution::published()->ofType('profesor')
            ->whereNotNull('banner_path')->latest()->limit(12)->get(),

        'eventos' => Event::upcoming()->limit(12)->get(),
    ]);
}

}

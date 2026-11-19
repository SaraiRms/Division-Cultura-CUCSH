<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    // Lista de noticias, 9 por página
    public function index(Request $request)
    {
        $datos = $request->validate([
            'q'    => ['nullable', 'string', 'max:100'],
            'anio' => ['nullable', 'integer', 'between:2000,2100'],
        ]);

        $q    = trim($datos['q'] ?? '');
        $anio = $datos['anio'] ?? null;

        $noticias = News::published()
            ->search($q)
            ->when($anio, fn ($query) => $query->whereYear('published_at', $anio))
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();   // la búsqueda se conserva al cambiar de página

        // Los años que tienen noticias, para el selector
        $anios = News::published()
            ->whereNotNull('published_at')
            ->selectRaw('YEAR(published_at) as anio')
            ->distinct()
            ->orderByDesc('anio')
            ->pluck('anio');

        return view('noticias.index', compact('noticias', 'q', 'anio', 'anios'));
    }

    // Una noticia completa
    public function show(News $news)
    {
        // Si alguien adivina el enlace de una noticia sin publicar, no se muestra
        abort_unless($news->status === 'published', 404);

        $relacionadas = News::published()
            ->where('id', '!=', $news->id)
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('noticias.show', [
            'noticia' => $news,
            'relacionadas' => $relacionadas,
        ]);
    }
}
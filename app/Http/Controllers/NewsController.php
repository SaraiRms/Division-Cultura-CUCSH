<?php

namespace App\Http\Controllers;

use App\Models\News;

class NewsController extends Controller
{
    // Lista de noticias, 9 por página
    public function index()
    {
        $noticias = News::published()->latest('published_at')->paginate(9);

        return view('noticias.index', compact('noticias'));
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
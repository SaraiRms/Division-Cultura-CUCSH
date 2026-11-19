<?php

namespace App\Http\Controllers;

use App\Models\Conversatorio;
use App\Models\DirectoryAdmin;
use App\Models\Event;
use App\Models\News;
use App\Models\Podcast;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $q = trim($request->validate(['q' => ['nullable', 'string', 'max:100']])['q'] ?? '');

        // Con menos de 2 letras no se busca (evita listar todo el sitio)
        if (mb_strlen($q) < 2) {
            return view('buscar', ['q' => $q, 'grupos' => collect(), 'corto' => $q !== '']);
        }

        $t = '%' . addcslashes($q, '%_\\') . '%';

        $grupos = collect([
            'Noticias' => News::published()->search($q)->latest('published_at')->limit(10)->get()
                ->map(fn ($n) => [
                    'titulo'  => $n->title,
                    'detalle' => str($n->summary)->limit(140),
                    'url'     => route('noticias.show', $n),
                ]),

            'Eventos' => Event::published()
                ->where(fn ($w) => $w->where('title', 'like', $t)->orWhere('description', 'like', $t)->orWhere('location', 'like', $t))
                ->orderByDesc('starts_at')->limit(10)->get()
                ->map(fn ($e) => [
                    'titulo'  => $e->title,
                    'detalle' => $e->starts_at->translatedFormat('j \d\e F, Y') . ($e->location ? ' · ' . $e->location : ''),
                    'url'     => route('eventos.show', $e),
                ]),

            'Conversatorios' => Conversatorio::query()
                ->where(fn ($w) => $w->where('title', 'like', $t)->orWhere('speaker', 'like', $t)->orWhere('description', 'like', $t))
                ->orderByDesc('scheduled_at')->limit(10)->get()
                ->map(fn ($c) => [
                    'titulo'  => $c->title,
                    'detalle' => $c->speaker ? 'Invitado(a): ' . $c->speaker : '',
                    'url'     => route('conversatorios.index'),
                ]),

            'Podcast' => Podcast::published()
                ->where(fn ($w) => $w->where('title', 'like', $t)->orWhere('description', 'like', $t))
                ->latest('published_at')->limit(10)->get()
                ->map(fn ($p) => [
                    'titulo'  => $p->title,
                    'detalle' => str(strip_tags((string) $p->description))->limit(140),
                    'url'     => route('podcast.index'),
                ]),

            'Directorio' => DirectoryAdmin::query()
                ->where(fn ($w) => $w->where('full_name', 'like', $t)->orWhere('cargo', 'like', $t)->orWhere('area_conocimiento', 'like', $t))
                ->orderBy('jerarquia')->orderBy('full_name')->limit(10)->get()
                ->map(fn ($d) => [
                    'titulo'  => $d->full_name,
                    'detalle' => $d->cargo,
                    'url'     => route('directorio.index'),
                ]),
        ])->filter(fn ($items) => $items->isNotEmpty());

        return view('buscar', ['q' => $q, 'grupos' => $grupos, 'corto' => false]);
    }
}
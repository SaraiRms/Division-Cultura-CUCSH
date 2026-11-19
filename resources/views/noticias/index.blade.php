@extends('layouts.public')

@section('content')

{{-- ////////////////////////  BANNER  //////////////////////// --}}
<section class="relative flex h-44 w-full items-center justify-center overflow-hidden bg-marino md:h-56">
    <img src="{{ asset('images/hero/hero-3.jpg') }}" alt=""
         class="absolute inset-0 h-full w-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-marino/70 via-black/55 to-vino/40"></div> {{-- Degradado --}}
        <div class="relative z-10 px-4 text-center text-white">
            <h1 class="text-4xl font-medium tracking-wide md:text-5xl">
                Noticias
            </h1>
        </div>
</section>

<section class="mx-auto max-w-6xl px-6 pb-12">

    {{-- ////////////////////////  BUSCADOR  //////////////////////// --}}
    <form method="GET" action="{{ route('noticias.index') }}" role="search"
          x-data="{ q: @js($q) }"
          class="relative z-10 -mt-8 rounded-xl border border-marino/10 bg-white p-4 shadow-lg sm:p-5">

        <div class="flex flex-col gap-3 sm:flex-row">
            <div class="relative flex-1">
                <label for="q" class="sr-only">Buscar noticias</label>
                <x-heroicon-o-magnifying-glass class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-marino/50" />

                <input id="q" x-ref="q" x-model="q" type="search" name="q" maxlength="100"
                       placeholder="Buscar por título o contenido…"
                       class="w-full rounded-lg border border-marino/15 bg-white py-3 pl-10 pr-10 text-marino
                              focus:border-vino focus:outline-none focus:ring-2 focus:ring-vino/20">

                {{-- Botón para borrar lo escrito --}}
                <button type="button" x-show="q" x-cloak @click="q = ''; $refs.q.focus()"
                        aria-label="Borrar búsqueda"
                        class="absolute right-2 top-1/2 flex h-7 w-7 -translate-y-1/2 items-center justify-center rounded-full text-marino/50 hover:bg-marino/10 hover:text-marino">
                    <x-heroicon-o-x-mark class="h-4 w-4" />
                </button>
            </div>

            {{-- El año elegido se conserva al buscar --}}
            @if ($anio)
                <input type="hidden" name="anio" value="{{ $anio }}">
            @endif

            <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-marino px-6 py-3 font-semibold text-white transition hover:bg-vino">
                Buscar
            </button>
        </div>

        {{-- Años: se filtra con un clic --}}
        @if ($anios->isNotEmpty())
            <div class="mt-4 flex flex-wrap items-center gap-2" role="group" aria-label="Filtrar por año">
                <span class="text-sm text-marino/60">Año:</span>

                <a href="{{ route('noticias.index', array_filter(['q' => $q])) }}"
                   @if (! $anio) aria-current="true" @endif
                   class="rounded-full px-3 py-1 text-sm transition {{ ! $anio ? 'bg-vino text-white' : 'bg-marino/5 text-marino hover:bg-marino/10' }}">
                    Todos
                </a>

                @foreach ($anios as $a)
                    <a href="{{ route('noticias.index', array_filter(['q' => $q, 'anio' => $a])) }}"
                       @if ((int) $anio === (int) $a) aria-current="true" @endif
                       class="rounded-full px-3 py-1 text-sm transition {{ (int) $anio === (int) $a ? 'bg-vino text-white' : 'bg-marino/5 text-marino hover:bg-marino/10' }}">
                        {{ $a }}
                    </a>
                @endforeach
            </div>
        @endif
    </form>

    {{-- Resumen de la búsqueda --}}
    @if ($q !== '' || $anio)
        <p class="mt-6 flex flex-wrap items-center gap-x-3 text-marino/75">
            <span>
                {{ $noticias->total() }} {{ $noticias->total() === 1 ? 'resultado' : 'resultados' }}
                @if ($q !== '') para “{{ $q }}” @endif
                @if ($anio) en {{ $anio }} @endif
            </span>
            <a href="{{ route('noticias.index') }}" class="text-sm font-semibold text-vino underline-offset-4 hover:underline">
                Limpiar filtros
            </a>
        </p>
    @endif

    {{-- ////////////////////////  TARJETAS  //////////////////////// --}}
    <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($noticias as $n)
            <a href="{{ route('noticias.show', $n) }}"
               class="group flex h-full flex-col overflow-hidden rounded-xl border border-marino/10 bg-white shadow-sm
                      transition-all duration-300 hover:-translate-y-1 hover:shadow-md
                      focus-visible:outline focus-visible:outline-2 focus-visible:outline-marino">

                <div class="relative aspect-video overflow-hidden bg-marino/5">
                    @if ($n->image_path)
                        <img src="{{ asset('storage/' . $n->image_path) }}" alt="" loading="lazy"
                             class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                    @else
                        <div class="flex h-full w-full items-center justify-center text-marino/25">
                            <x-heroicon-o-newspaper class="h-14 w-14" />
                        </div>
                    @endif

                    @if ($n->is_featured)
                        <span class="absolute left-3 top-3 rounded-full bg-vino px-3 py-1 text-xs font-semibold uppercase tracking-wider text-white shadow">
                            Destacada
                        </span>
                    @endif
                </div>

                <div class="flex flex-1 flex-col p-5">
                    <time datetime="{{ $n->published_at?->toDateString() }}"
                          class="text-xs font-semibold uppercase tracking-wider text-vino">
                        {{ $n->published_at?->translatedFormat('j \d\e F, Y') }}
                    </time>

                    <h2 class="mt-2 line-clamp-2 text-xl font-semibold leading-snug text-marino">{{ $n->title }}</h2>
                    <p class="mt-2 line-clamp-3 text-base text-marino/75">{{ $n->summary }}</p>

                    <span class="mt-auto inline-flex items-center gap-1 pt-4 text-sm font-semibold text-vino transition-all group-hover:gap-2">
                        Leer noticia <span aria-hidden="true">→</span>
                    </span>
                </div>
            </a>
        @empty
            <div class="rounded-xl border-2 border-dashed border-vino/25 bg-vino/[0.03] p-10 text-center sm:col-span-2 lg:col-span-3">
                <x-heroicon-o-newspaper class="mx-auto h-10 w-10 text-vino/50" />
                <p class="mt-3 text-lg font-semibold text-marino">
                    {{ ($q !== '' || $anio) ? 'No encontramos noticias con esos criterios' : 'Aún no hay noticias publicadas' }}
                </p>
                @if ($q !== '' || $anio)
                    <p class="mt-1 text-marino/70">Prueba con otra palabra o elige otro año.</p>
                    <a href="{{ route('noticias.index') }}" class="mt-4 inline-block font-semibold text-vino underline-offset-4 hover:underline">
                        Ver todas las noticias
                    </a>
                @endif
            </div>
        @endforelse
    </div>

    <div class="mt-10">{{ $noticias->links() }}</div>
</section>

@endsection
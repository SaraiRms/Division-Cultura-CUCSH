@extends('layouts.public')

@section('content')

  {{-- ////////////////////////  BANNER  //////////////////////// --}}
<section class="relative overflow-hidden bg-white">

    {{-- Foto a la derecha. Si no existe public/images/hero.jpg, se ve el fondo crema --}}
    <div class="absolute inset-y-0 right-0 w-full bg-crema bg-cover bg-center lg:w-3/5"
         style="background-image: url('{{ asset('images/hero.jpg') }}')"></div>

    {{-- Panel blanco con el borde inclinado --}}
    <div class="relative bg-white py-14 pl-6 pr-16 lg:w-[58%] lg:pl-12 [clip-path:polygon(0_0,100%_0,88%_100%,0_100%)]">
        <p class="text-xs tracking-widest text-marino">CONOCIMIENTO | CULTURA | SOCIEDAD</p>
        <div class="mt-4 border-l-4 border-vino pl-5">
            <h1 class="text-5xl leading-tight text-marino">División de Estudios<br>de la Cultura</h1>
            <p class="mt-4 max-w-sm text-sm text-marino">
                Espacio académico dedicado al diálogo, la investigación y la difusión del conocimiento cultural
            </p>
        </div>
    </div>

</section>

{{-- ACCESOS RÁPIDOS --}}
@php
    $accesos = [
        ['Presentación',  'Conoce nuestra misión, objetivos y el quehacer de la división', 'heroicon-o-academic-cap', route('noticias.index')],
        ['Departamentos', 'Conoce nuestra oferta académica en el departamento de la Cultura', 'heroicon-o-book-open', route('departamentos.index') . '#presentacion'],
        ['Eventos',       'Mantente al tanto de nuestras conferencias, talleres y actividades académicas', 'heroicon-o-calendar-days', route('eventos.index')],
        ['Noticias',      'Entérate de las últimas novedades, proyectos y convocatorias', 'heroicon-o-newspaper', '#'],
    ];
@endphp

<section class="bg-neutral-100">
    <div class="mx-auto grid max-w-6xl md:grid-cols-4 md:divide-x md:divide-neutral-300">
        
        @foreach ($accesos as [$titulo, $texto, $icono, $enlace])
            <a href="{{ route('departamentos.index') . '#departamentos' }}" class="block px-6 py-10 text-center hover:bg-white/60">
                <x-dynamic-component :component="$icono" class="mx-auto h-16 w-16 text-vino" />
                <h3 class="mt-4 text-xl text-vino">{{ $titulo }}</h3>
                <p class="mt-3 text-sm text-marino">{{ $texto }}</p>
            </a>
        @endforeach

    </div>
</section>



{{--  ////////////////////////  NOTICIAS  ////////////////////////  --}}

<section class="mx-auto max-w-6xl px-6 py-12">
    <h2 class="flex items-center gap-3 text-xl font-bold text-vino">
        <span class="h-px w-14 bg-vino"></span> Noticias
    </h2>

    <div class="mt-5 grid gap-8 lg:grid-cols-[1.25fr_1fr_1fr]">

        {{-- 1) Carrusel de destacadas --}}
        @if ($destacadas->isNotEmpty())
            <div class="relative h-80 overflow-hidden bg-crema lg:h-auto lg:min-h-80"
                 x-data="{ actual: 0, total: {{ $destacadas->count() }} }"
                 x-init="if (total > 1) setInterval(() => actual = (actual + 1) % total, 6000)">

                @foreach ($destacadas as $i => $n)
                    <article x-cloak x-show="actual === {{ $i }}" x-transition.opacity class="absolute inset-0">
                        @if ($n->image_path)
                            <img src="{{ asset('storage/' . $n->image_path) }}" alt="{{ $n->title }}"
                                 class="h-full w-full object-cover">
                        @endif

                        <div class="absolute bottom-0 left-0 w-3/5 bg-vino p-5 text-white">
                            <p class="text-[10px] tracking-[0.2em]">DESTACADO</p>
                            <h3 class="mt-2 text-2xl leading-snug">{{ $n->title }}</h3>
                            <p class="mt-2 text-xs text-white/80">{{ Str::limit($n->summary, 110) }}</p>
                            <a href="{{ route('noticias.show', $n) }}" class="mt-3 inline-block text-xs underline">Ver más →</a>
                        </div>
                    </article>
                @endforeach

                {{-- Puntitos para cambiar de noticia --}}
                <div class="absolute bottom-3 right-3 flex gap-2">
                    @foreach ($destacadas as $i => $n)
                        <button type="button" @click="actual = {{ $i }}"
                                class="h-2.5 w-2.5 rounded-full"
                                :class="actual === {{ $i }} ? 'bg-vino' : 'bg-white/80'"
                                aria-label="Noticia {{ $i + 1 }}"></button>
                    @endforeach
                </div>
            </div>
        @else
            <div class="flex items-center justify-center bg-crema p-8 text-center text-marino">
                Aún no hay noticias destacadas.
            </div>
        @endif

        {{-- 2) Lo más reciente --}}
        <div>
            <h3 class="text-2xl text-vino">Lo más reciente</h3>
            <ul class="mt-4 divide-y divide-neutral-200">
                @forelse ($recientes as $n)
                    <li class="flex gap-3 py-3">
                        @if ($n->image_path)
                            <img src="{{ asset('storage/' . $n->image_path) }}" alt=""
                                 class="h-16 w-24 shrink-0 object-cover">
                        @else
                            <div class="h-16 w-24 shrink-0 bg-crema"></div>
                        @endif
                        <div>
                            <a href="{{ route('noticias.show', $n) }}" class="text-sm text-marino hover:underline">{{ $n->title }}</a>
                            <p class="mt-1 text-xs text-neutral-500">
                                {{ $n->published_at?->translatedFormat('d M, Y') }}
                            </p>
                        </div>
                    </li>
                @empty
                    <li class="py-3 text-sm text-neutral-500">Todavía no hay noticias publicadas.</li>
                @endforelse
            </ul>
            <a href="{{ route('noticias.index') }}" class="mt-3 inline-block text-xs text-vino underline">Ver más noticias →</a>
        </div>

        {{-- 3) Tarjeta del Laboratorio / Podcast --}}
        <div class="flex flex-col border border-neutral-200 bg-white p-6 shadow-[6px_6px_0_0_rgba(120,29,38,0.25)]">
            <p class="text-[11px] tracking-[0.25em] text-neutral-500">Proyectos</p>
            <h3 class="mt-2 text-2xl font-bold text-marino">Laboratorio<br>“Edición Digital”</h3>
            <p class="mt-3 text-xs text-neutral-500">
                Un espacio para la creatividad y el diseño alrededor de los espacios culturales.
            </p>
            <a href="#" class="mt-4 text-xs text-vino underline">Conocer el proyecto →</a>
            <x-heroicon-o-microphone class="mx-auto mt-auto h-24 w-24 text-vino/70" />
        </div>
    </div>
</section>



{{--  ////////////////////////  CONVERSATORIOS  ////////////////////////  --}}

<section class="mx-auto max-w-6xl px-6 pb-12">
    <x-carrusel titulo="Conversatorios">
        @forelse ($conversatorios as $c)
            <x-carrusel-item>
                <article class="h-full bg-neutral-50 shadow-sm">
                    @if ($c->youtube_thumbnail)
                        <img src="{{ $c->youtube_thumbnail }}" alt="{{ $c->title }}" class="h-36 w-full object-cover">
                    @else
                        <div class="h-36 w-full bg-crema"></div>
                    @endif

                    <div class="p-4 text-center">
                        @if ($c->status === 'live')
                            <span class="mb-2 inline-block bg-vino px-2 py-0.5 text-[10px] tracking-widest text-white">EN VIVO</span>
                        @endif
                        <h3 class="text-lg leading-snug text-marino">{{ $c->title }}</h3>
                        <p class="mt-2 text-sm text-neutral-600">{{ Str::limit($c->description, 90) }}</p>
                        <a href="{{ $c->stream_url ?? '#' }}" target="_blank" rel="noopener"
                           class="mt-3 inline-block text-sm text-vino underline">Ver →</a>
                    </div>
                </article>
            </x-carrusel-item>
        @empty
            <p class="px-3 text-sm text-neutral-500">Aún no hay conversatorios.</p>
        @endforelse
    </x-carrusel>
</section>

{{--  ////////////////////////  BANNER CÁTEDRA JULIO CORTÁZAR  ////////////////////////  --}}
<section class="mx-auto max-w-6xl px-6 pb-12">
    <a href="{{ config('site.catedra.url') }}" target="_blank" rel="noopener" class="block">
        @if (file_exists(public_path(config('site.catedra.imagen'))))
            <img src="{{ asset(config('site.catedra.imagen')) }}" alt="Cátedra Julio Cortázar" class="w-full">
        @else
            <div class="flex h-40 items-center justify-center bg-marino text-2xl text-white">
                Cátedra Julio Cortázar
            </div>
        @endif
    </a>
</section>


{{--  ////////////////////////  PARTICIPACIÓN  ////////////////////////  --}}

@php
    $participaciones = [
        [
            'titulo'  => 'Participación estudiantil',
            'texto'   => '¡Comparte tu conocimiento en el carrusel de banners a nuestra comunidad académica, queremos escucharte!',
            'items'   => $aportacionesAlumnos,
            'enlace'  => '#',
        ],
        [
            'titulo'  => 'Participación de académicos',
            'texto'   => '¡Comparte tu conocimiento, artículos y participaciones docentes en el carrusel de banners a nuestra comunidad académica, queremos escucharte!',
            'items'   => $aportacionesProfesores,
            'enlace'  => '#',
        ],
    ];
@endphp

@foreach ($participaciones as $p)
    <section class="mx-auto max-w-6xl px-6 pb-12">
        <div class="mb-4 flex flex-col items-center justify-between gap-4 bg-neutral-50 p-5 sm:flex-row">
            <div>
                <h2 class="text-2xl text-vino">{{ $p['titulo'] }}</h2>
                <p class="mt-1 max-w-2xl text-sm text-marino">{{ $p['texto'] }}</p>
            </div>
            <a href="{{ $p['enlace'] }}"
               class="inline-flex shrink-0 items-center gap-2 bg-marino px-6 py-3 text-lg text-white hover:opacity-90">
                Participar <x-heroicon-o-arrow-right class="h-5 w-5" />
            </a>
        </div>

        <x-carrusel>
            @forelse ($p['items'] as $a)
                <x-carrusel-item>
                    <a href="#" class="block overflow-hidden shadow-sm">
                        <img src="{{ asset('storage/' . $a->banner_path) }}" alt="{{ $a->title }}"
                             class="aspect-[4/3] w-full object-cover">
                    </a>
                </x-carrusel-item>
            @empty
                <p class="px-3 text-sm text-neutral-500">Aún no hay participaciones publicadas.</p>
            @endforelse
        </x-carrusel>
    </section>
@endforeach


{{--  ////////////////////////  PRÓXIMOS EVENTOS  ////////////////////////  --}}

<section class="mx-auto max-w-6xl px-6 pb-16">
    <x-carrusel titulo="Próximos eventos">
        @forelse ($eventos as $e)
            <x-carrusel-item>
                <a href="{{ route('eventos.show', $e) }}" class="block h-full overflow-hidden bg-neutral-50 shadow-sm transition hover:shadow-md">
                    @if ($e->image_path)
                        <img src="{{ asset('storage/' . $e->image_path) }}" alt="{{ $e->title }}"
                             class="h-32 w-full object-cover">
                    @else
                        <div class="h-32 w-full bg-crema"></div>
                    @endif

                    <div class="flex items-center gap-3 p-3">
                        <div class="bg-neutral-200 px-3 py-2 text-center leading-none">
                            <span class="block text-2xl text-marino">{{ $e->starts_at->format('d') }}</span>
                            <span class="mt-1 block text-xs uppercase text-marino">{{ $e->starts_at->translatedFormat('M') }}</span>
                        </div>
                        <div>
                            <p class="text-sm leading-snug text-marino">{{ $e->title }}</p>
                            <p class="mt-1 text-xs text-neutral-500">{{ $e->location }}</p>
                        </div>
                    </div>
                </a>
            </x-carrusel-item>
        @empty
            <p class="px-3 text-sm text-neutral-500">No hay eventos próximos por ahora.</p>
        @endforelse
    </x-carrusel>
</section>


@endsection
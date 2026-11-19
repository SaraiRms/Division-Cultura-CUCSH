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

<section class="mx-auto max-w-6xl px-6 py-12">

    {{-- Buscador --}}
    <form method="GET" action="{{ route('noticias.index') }}" role="search"
          class="flex flex-col gap-3 sm:flex-row">
        <div class="relative flex-1">
            <label for="q" class="sr-only">Buscar noticias</label>
            <x-heroicon-o-magnifying-glass class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-neutral-400" />
            <input id="q" type="search" name="q" value="{{ $q }}" maxlength="100"
                   placeholder="Buscar por título o contenido…"
                   class="w-full border border-neutral-300 bg-white py-3 pl-10 pr-3 text-marino">
        </div>

        <label for="anio" class="sr-only">Año</label>
        <select id="anio" name="anio" class="border border-neutral-300 bg-white px-3 py-3 text-marino">
            <option value="">Todos los años</option>
            @foreach ($anios as $a)
                <option value="{{ $a }}" @selected((int) $anio === (int) $a)>{{ $a }}</option>
            @endforeach
        </select>

        <button type="submit" class="bg-marino px-6 py-3 text-white hover:bg-vino">Buscar</button>

        @if ($q !== '' || $anio)
            <a href="{{ route('noticias.index') }}"
               class="flex items-center justify-center px-3 py-3 text-vino underline">Limpiar</a>
        @endif
    </form>

    @if ($q !== '' || $anio)
        <p class="mt-4 text-sm text-neutral-600">
            {{ $noticias->total() }} {{ $noticias->total() === 1 ? 'resultado' : 'resultados' }}
        </p>
    @endif

    <div class="mt-8 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($noticias as $n)
            <a href="{{ route('noticias.show', $n) }}"
               class="group block overflow-hidden bg-neutral-50 shadow-sm transition hover:shadow-md">
                @if ($n->image_path)
                    <img src="{{ asset('storage/' . $n->image_path) }}" alt="{{ $n->title }}"
                         class="h-48 w-full object-cover">
                @else
                    <div class="h-48 w-full bg-neutral-200"></div>
                @endif

                <div class="p-5">
                    <p class="text-xs text-neutral-500">
                        {{ $n->published_at?->translatedFormat('j \d\e F, Y') }}
                    </p>
                    <h2 class="mt-2 text-xl leading-snug text-marino group-hover:underline">{{ $n->title }}</h2>
                    <p class="mt-2 text-sm text-neutral-600">{{ Str::limit($n->summary, 120) }}</p>
                </div>
            </a>
        @empty
            <p class="text-neutral-500 sm:col-span-2 lg:col-span-3">
                {{ ($q !== '' || $anio) ? 'No encontramos noticias con esos criterios.' : 'Aún no hay noticias publicadas.' }}
            </p>
        @endforelse
    </div>

    <div class="mt-10">{{ $noticias->links() }}</div>
</section>


{{-- <section class="mx-auto max-w-6xl px-6 py-12"> 
    <div class="mt-8 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">

        @forelse ($noticias as $n)
            <a href="{{ route('noticias.show', $n) }}"
               class="group block overflow-hidden bg-neutral-50 shadow-sm transition hover:shadow-md">
                @if ($n->image_path)
                    <img src="{{ asset('storage/' . $n->image_path) }}" alt="{{ $n->title }}"
                         class="h-48 w-full object-cover">
                @else
                    <div class="h-48 w-full bg-neutral-200"></div>
                @endif

                <div class="p-5">
                    <p class="text-xs text-neutral-500">
                        {{ $n->published_at?->translatedFormat('j \d\e F, Y') }}
                    </p>
                    <h2 class="mt-2 text-xl leading-snug text-marino group-hover:underline">{{ $n->title }}</h2>
                    <p class="mt-2 text-sm text-neutral-600">{{ Str::limit($n->summary, 120) }}</p>
                </div>
            </a>
        @empty
            <p class="text-neutral-500 sm:col-span-2 lg:col-span-3">Aún no hay noticias publicadas.</p>
        @endforelse

    </div>

    <div class="mt-10">{{ $noticias->links() }}</div>
</section> --}}
@endsection
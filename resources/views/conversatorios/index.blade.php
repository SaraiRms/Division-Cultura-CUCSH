@extends('layouts.public')

@section('content')

@php
    $estados = [
        'live'      => ['EN VIVO', 'bg-vino text-white'],
        'scheduled' => ['PROGRAMADO', 'bg-marino text-white'],
        'finished'  => ['TRANSMISIÓN ANTERIOR', 'bg-neutral-200 text-marino'],
    ];
@endphp

<section class="mx-auto max-w-6xl px-6 py-12">
    <h1 class="flex items-center gap-3 text-3xl text-vino">
        <span class="h-px w-14 bg-vino"></span> Conversatorio académico
    </h1>
    <p class="mt-4 max-w-3xl text-marino">
        Escucha a nuestros invitados en vivo. Consulta nuestro canal de YouTube y conoce más acerca de los
        temas abordados en esta sección.
    </p>

    {{-- ========== EL GRANDE ========== --}}
    @if ($principal)
        @php $estado = $estados[$principal->status] ?? ['', 'hidden']; @endphp

        <div class="mt-8 grid gap-8 lg:grid-cols-[3fr_2fr]">
            {{-- Reproductor --}}
            @if ($principal->youtube_id)
                <div class="aspect-video w-full overflow-hidden bg-black">
                    <iframe class="h-full w-full"
                            src="https://www.youtube-nocookie.com/embed/{{ $principal->youtube_id }}"
                            title="{{ $principal->title }}" loading="lazy" allowfullscreen
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            referrerpolicy="strict-origin-when-cross-origin"></iframe>
                </div>
            @else
                <div class="flex aspect-video w-full items-center justify-center bg-neutral-200 text-neutral-500">
                    El video estará disponible próximamente.
                </div>
            @endif

            {{-- Datos --}}
            <div>
                <span class="inline-flex items-center gap-2 px-3 py-1 text-xs tracking-widest {{ $estado[1] }}">
                    @if ($principal->status === 'live')
                        <span class="h-2 w-2 animate-pulse rounded-full bg-white"></span>
                    @endif
                    {{ $estado[0] }}
                </span>

                <h2 class="mt-4 text-3xl leading-tight text-vino">{{ $principal->title }}</h2>

                @if ($principal->speaker)
                    <p class="mt-2 text-marino">Invitado: {{ $principal->speaker }}</p>
                @endif

                @if ($principal->scheduled_at)
                    <p class="mt-2 flex items-center gap-2 text-sm text-neutral-600">
                        <x-heroicon-o-calendar-days class="h-5 w-5 text-vino" />
                        {{ ucfirst($principal->scheduled_at->translatedFormat('l j \d\e F \d\e Y, H:i')) }} h
                    </p>
                @endif

                <x-texto-rico :texto="$principal->description" class="mt-4" />

                @if ($principal->stream_url)
                    <a href="{{ $principal->stream_url }}" target="_blank" rel="noopener"
                       class="mt-5 inline-block text-sm text-vino underline">Abrir en YouTube →</a>
                @endif
            </div>
        </div>
    @else
        <p class="mt-8 text-neutral-500">Aún no hay conversatorios registrados.</p>
    @endif

    {{-- ========== ANTERIORES ========== --}}
    @if ($anteriores->isNotEmpty())
        <h2 class="mt-14 flex items-center gap-3 text-xl font-bold text-vino">
            <span class="h-px w-14 bg-vino"></span> Conversatorios anteriores
        </h2>

        <div class="mt-5 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($anteriores as $c)
                <a href="{{ $c->stream_url ?? '#' }}" target="_blank" rel="noopener"
                   class="group block overflow-hidden bg-neutral-50 shadow-sm transition hover:shadow-md">
                    <div class="relative">
                        @if ($c->youtube_thumbnail)
                            <img src="{{ $c->youtube_thumbnail }}" alt="{{ $c->title }}"
                                 class="aspect-video w-full object-cover">
                        @else
                            <div class="aspect-video w-full bg-neutral-200"></div>
                        @endif
                        <span class="absolute inset-0 flex items-center justify-center">
                            <x-heroicon-s-play-circle class="h-14 w-14 text-white/90 drop-shadow-lg transition group-hover:scale-110" />
                        </span>
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg leading-snug text-marino group-hover:underline">{{ $c->title }}</h3>
                        @if ($c->speaker)
                            <p class="mt-1 text-sm text-neutral-600">{{ $c->speaker }}</p>
                        @endif
                        <p class="mt-1 text-xs text-neutral-500">
                            {{ $c->scheduled_at?->translatedFormat('j \d\e F, Y') }}
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
    @endif

    {{-- Los demás, en el canal --}}
    <div class="mt-12 text-center">
        <a href="{{ config('site.canal_conversatorios', '#') }}" target="_blank" rel="noopener"
           class="inline-flex items-center gap-2 bg-marino px-6 py-3 text-lg text-white hover:opacity-90">
            <x-heroicon-o-play-circle class="h-6 w-6" /> Ver más en nuestro canal de YouTube
        </a>
    </div>
</section>
@endsection
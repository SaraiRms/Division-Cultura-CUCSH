@extends('layouts.public')

@section('content')

@php
    $estados = [
        'live'      => ['EN VIVO', 'bg-vino text-white'],
        'scheduled' => ['PROGRAMADO', 'bg-marino text-white'],
        'finished'  => ['TRANSMISIÓN ANTERIOR', 'bg-neutral-200 text-marino'],
    ];
@endphp

{{-- ////////////////////////  BANNER CON VIDEO  //////////////////////// --}}
<section class="relative flex h-44 w-full items-center justify-center overflow-hidden bg-marino md:h-56">
    <video autoplay muted loop playsinline
           poster="{{ asset('images/conversatorio.jpg') }}"
           class="absolute inset-0 h-full w-full object-cover motion-reduce:hidden">
            <source src="{{ asset('videos/conversatorio.mp4') }}" type="video/mp4">
        </video>
        
        <img src="{{ asset('images/conversatorio.jpg') }}" alt=""
            class="absolute inset-0 hidden h-full w-full object-cover motion-reduce:block">
        <div class="absolute inset-0 bg-gradient-to-r from-marino/70 via-black/55 to-vino/30"></div> {{-- Degradado --}}
        <div class="relative z-10 px-4 text-center text-white">
            <h1 class="mt-2 text-4xl font-medium tracking-wide md:text-5xl">
                Conversatorio
            </h1>
            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-white/80">
                Académico
            </p>
        </div>
</section>

<section class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:py-14">
    <div class="pb-7">
        <a href="{{ route('home') }}"
        class="inline-flex items-center justify-center gap-2 rounded-lg
                bg-vino px-5 py-3 text-sm font-semibold text-white
                shadow-sm transition duration-200
                hover:opacity-90 hover:shadow-md
                focus-visible:outline focus-visible:outline-2
                focus-visible:outline-offset-4 focus-visible:outline-marino">
            <x-heroicon-o-arrow-left class="h-5 w-5" aria-hidden="true" />
            <span>Regresar al inicio</span>
        </a>
    </div>

{{-- Encabezado --}}
    <div class="max-w-3xl">
        <p class="mt-5 text-base leading-relaxed text-marino sm:text-lg">
            Escucha a nuestros invitados en vivo. Consulta nuestro canal de YouTube y conoce más acerca de los
            temas abordados en esta sección.
        </p>
    </div>

{{-- ========== EL GRANDE ========== --}}
    @if ($principal)
        @php 
            $estado = $estados[$principal->status] ?? ['', 'hidden']; 
        @endphp

         <div class="mt-15 grid gap-8 lg:grid-cols-[3fr_2fr]">
            {{-- Reproductor: la miniatura se cambia por el video solo al dar clic --}}
            @if ($principal->youtube_id)
                <div class="relative aspect-video w-full overflow-hidden bg-black" x-data="{ activo: false }">
                    <button type="button" x-show="!activo" @click="activo = true"
                            aria-label="Reproducir: {{ $principal->title }}"
                            class="group absolute inset-0 h-full w-full">
                        @if ($principal->youtube_thumbnail)
                            <img src="{{ $principal->youtube_thumbnail }}" alt="" class="h-full w-full object-cover">
                        @endif
                        <span class="absolute inset-0 flex items-center justify-center bg-black/25 transition group-hover:bg-black/10">
                            <x-heroicon-s-play-circle class="h-20 w-20 text-white/90 drop-shadow-lg transition group-hover:scale-110" />
                        </span>
                    </button>
                    <template x-if="activo">
                        <iframe class="absolute inset-0 h-full w-full"
                                src="https://www.youtube-nocookie.com/embed/{{ $principal->youtube_id }}?autoplay=1&rel=0"
                                title="{{ $principal->title }}" allowfullscreen
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                referrerpolicy="strict-origin-when-cross-origin"></iframe>
                    </template>
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
                <a @if ($c->stream_url) href="{{ $c->stream_url }}" target="_blank" rel="noopener" @endif
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
</section>

{{--  ////////////////////////  INVITACIÓN AL CANAL  //////////////////////// --}}
@php 
    $canal = config('site.redes.YouTube'); 
@endphp

@if ($canal && $canal !== '#')
    <section class="mx-auto max-w-6xl px-6 pb-8 pt-20">
        <div class="flex flex-col items-start justify-between gap-6 rounded-xl border border-marino/10 bg-white p-6 shadow-sm sm:flex-row sm:items-center sm:p-8">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-vino">¡Mira más de nuestras charlas!</p>
                <h2 class="mt-1 text-xl font-semibold text-marino">Mira más contenido y variedad de nuestros temas</h2>
                <p class="mt-1 text-sm text-marino/75">
                    Suscríbete a nuestro canal oficial para recibir notificaciones de nuevo contenido.
                </p>
            </div>

            <a href="{{ $canal }}" target="_blank" rel="noopener"
               class="inline-flex shrink-0 items-center gap-2 rounded-lg bg-marino px-5 py-3 text-sm font-semibold text-white transition hover:bg-vino">
                <x-heroicon-o-play-circle class="h-5 w-5" /> Ver canal para más contenido
            </a>
        </div>
    </section>
@endif

@endsection
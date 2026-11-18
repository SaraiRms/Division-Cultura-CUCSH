@extends('layouts.public')

@section('content')

<section>
    <div class="relative flex h-40 w-full items-center justify-center overflow-hidden bg-gray-700 md:h-48">
        <video autoplay muted loop playsinline
                poster="{{ asset('images/podcast.png') }}"
                class="absolute inset-0 h-full w-full object-cover">
            <source src="{{ asset('videos/vid_pod.mp4') }}" type="video/mp4">
        </video>
        <div class="absolute inset-0 bg-black/50"></div>
        <h1 class="relative z-10 px-4 text-center text-7xl tracking-wide text-white md:text-5xl">
            Podcast
        </h1>
    </div>
</section>

<section class="mx-auto max-w-6xl px-6 py-6">
    <a href="{{ route('home') }}"
        class="inline-flex items-center justify-center gap-3 rounded-lg
        bg-vino px-5 py-3 text-sm font-semibold text-white
        shadow-sm transition duration-200
        hover:opacity-90 hover:shadow-md
        focus-visible:outline focus-visible:outline-2
        focus-visible:outline-offset-4 focus-visible:outline-marino">

    <x-heroicon-o-arrow-left class="h-5 w-5" aria-hidden="true" />

    <span>Regresar al inicio</span>
    </a>
</section>

<section class="mx-auto max-w-6xl mt-6 px-6 py-5">
    <h2 class="flex items-center gap-3 text-3xl text-vino">
        <span class="h-px w-14 bg-vino"></span> Laboratorio Digital 
    </h2>

    <div class="mt-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <p class="max-w-3xl text-marino">
            Laboratorio “Edición Digital”: un espacio para la creatividad y el diseño alrededor de los
            espacios culturales.
        </p>
    </div>

    <div class="mt-15 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($podcasts as $p)
            <a @if ($p->video_url) href="{{ $p->video_url }}" target="_blank" rel="noopener" @endif
               class="group block overflow-hidden bg-neutral-50 shadow-sm transition hover:shadow-md">

                <div class="relative">
                    @php
                        $imagen = $p->image_path ? asset('storage/' . $p->image_path) : $p->youtube_thumbnail;
                    @endphp

                    @if ($imagen)
                        <img src="{{ $imagen }}" alt="{{ $p->title }}" class="aspect-video w-full object-cover">
                    @else
                        <div class="aspect-video w-full bg-neutral-200"></div>
                    @endif

                    <span class="absolute inset-0 flex items-center justify-center">
                        <x-heroicon-s-play-circle class="h-14 w-14 text-white/90 drop-shadow-lg transition group-hover:scale-110" />
                    </span>
                </div>

                <div class="p-5">
                    <p class="text-xs text-neutral-500">{{ $p->published_at?->translatedFormat('j \d\e F, Y') }}</p>
                    <h2 class="mt-1 text-xl leading-snug text-marino group-hover:underline">{{ $p->title }}</h2>
                    <p class="mt-2 text-sm text-neutral-600">{{ Str::limit(strip_tags($p->description), 140) }}</p>
                    <span class="mt-3 inline-block text-sm text-vino underline">Ver en YouTube →</span>
                </div>
            </a>
        @empty
            <p class="text-neutral-500 sm:col-span-2 lg:col-span-3">Aún no hay episodios publicados.</p>
        @endforelse
    </div>

    <div class="mt-10">{{ $podcasts->links() }}</div>
</section>

<section class="mx-auto max-w-6xl px-6">
        <a href="{{ config('site.redes.Spotify') ?: config('site.redes.YouTube') }}" target="_blank" rel="noopener"
            class="inline-flex shrink-0 items-center gap-2 bg-marino px-5 py-2.5 text-white hover:opacity-90">
            <x-heroicon-o-play-circle class="h-6 w-6" /> Ver canal para más contenido
        </a>
</section>

@endsection
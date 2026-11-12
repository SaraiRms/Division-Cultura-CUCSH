@extends('layouts.public')

@section('content')
<article class="mx-auto max-w-3xl px-6 py-12">
    <a href="{{ route('noticias.index') }}" class="text-sm text-vino underline">← Todas las noticias</a>

    <h1 class="mt-4 text-4xl leading-tight text-marino">{{ $noticia->title }}</h1>
    <p class="mt-2 text-sm text-neutral-500">
        {{ $noticia->published_at?->translatedFormat('j \d\e F, Y') }}
    </p>

    @if ($noticia->image_path)
        <img src="{{ asset('storage/' . $noticia->image_path) }}" alt="{{ $noticia->title }}"
             class="mt-6 w-full object-cover">
    @endif

    @if ($noticia->summary)
        <p class="mt-6 text-xl text-neutral-700">{{ $noticia->summary }}</p>
    @endif

    {{-- El texto viene del editor del panel (ya trae su HTML), por eso se imprime con {!! !!} --}}
    <div class="contenido mt-6">{!! $noticia->body !!}</div>
</article>

@if ($relacionadas->isNotEmpty())
    <section class="mx-auto max-w-6xl px-6 pb-16">
        <h2 class="flex items-center gap-3 text-xl font-bold text-vino">
            <span class="h-px w-14 bg-vino"></span> Más noticias
        </h2>
        <div class="mt-5 grid gap-6 sm:grid-cols-3">
            @foreach ($relacionadas as $r)
                <a href="{{ route('noticias.show', $r) }}" class="block bg-neutral-50 p-4 shadow-sm hover:shadow-md">
                    <p class="text-xs text-neutral-500">{{ $r->published_at?->translatedFormat('j \d\e F, Y') }}</p>
                    <h3 class="mt-1 text-lg leading-snug text-marino">{{ $r->title }}</h3>
                </a>
            @endforeach
        </div>
    </section>
    
@endif
@endsection
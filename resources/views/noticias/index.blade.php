@extends('layouts.public')

@section('content')
<section class="mx-auto max-w-6xl px-6 py-12">
    <h1 class="flex items-center gap-3 text-3xl text-vino">
        <span class="h-px w-14 bg-vino"></span> Noticias
    </h1>

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
</section>
@endsection
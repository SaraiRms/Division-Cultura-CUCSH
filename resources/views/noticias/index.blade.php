@extends('layouts.public')

@section('content')
<section>
    <div class="relative flex h-40 w-full items-center justify-center bg-gray-900 bg-cover bg-center md:h-48"
         style="background-image: url('{{ asset('images/hero/hero-3.jpg') }}')">
        <div class="absolute inset-0 bg-black/50"></div>
        <h1 class="relative z-10 px-4 text-center text-3xl tracking-wide text-white md:text-5xl">
            Noticias
        </h1>
    </div>
</section>

<section class="mx-auto max-w-6xl px-6 py-12">


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
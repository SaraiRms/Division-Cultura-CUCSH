@extends('layouts.public')

@section('content')
<article class="mx-auto max-w-3xl px-6 py-12">
    <a href="{{ route('eventos.index', ['mes' => $evento->starts_at->format('Y-m')]) }}"
       class="text-sm text-vino underline">← Volver al calendario</a>

    <h1 class="mt-4 text-4xl leading-tight text-marino">{{ $evento->title }}</h1>

    <ul class="mt-4 space-y-2 text-marino">
        <li class="flex items-center gap-2">
            <x-heroicon-o-calendar-days class="h-5 w-5 text-vino" />
            {{ ucfirst($evento->starts_at->translatedFormat('l j \d\e F \d\e Y')) }}
        </li>
        <li class="flex items-center gap-2">
            <x-heroicon-o-clock class="h-5 w-5 text-vino" />
            {{ $evento->starts_at->format('H:i') }} h
        </li>
        @if ($evento->location)
            <li class="flex items-center gap-2">
                <x-heroicon-o-map-pin class="h-5 w-5 text-vino" />
                {{ $evento->location }}
            </li>
        @endif
    </ul>

    @if ($evento->image_path)
        <img src="{{ asset('storage/' . $evento->image_path) }}" alt="{{ $evento->title }}"
             class="mt-6 w-full object-cover">
    @endif

    <div class="contenido mt-6">{!! $evento->description !!}</div>
</article>
@endsection
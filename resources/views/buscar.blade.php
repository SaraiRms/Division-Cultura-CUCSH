@extends('layouts.public')

@section('title', 'Buscar')

@section('content')
<section class="mx-auto max-w-4xl px-6 py-12">
    <h1 class="text-3xl text-vino">Buscar en el sitio</h1>

    <form method="GET" action="{{ route('buscar') }}" role="search" class="mt-6 flex gap-2">
        <label for="q" class="sr-only">Buscar</label>
        <input id="q" type="search" name="q" value="{{ $q }}" maxlength="100" required minlength="2"
               class="w-full border border-neutral-300 bg-white px-4 py-3 text-marino"
               placeholder="Noticias, eventos, personas…">
        <button type="submit" class="bg-marino px-6 py-3 text-white hover:bg-vino">Buscar</button>
    </form>

    @if ($corto)
        <p class="mt-8 text-neutral-600">Escribe al menos 2 letras para buscar.</p>
    @elseif ($q !== '' && $grupos->isEmpty())
        <p class="mt-8 text-neutral-600">No encontramos resultados para “{{ $q }}”.</p>
    @endif

    @foreach ($grupos as $nombre => $items)
        <div class="mt-10">
            <h2 class="flex items-center gap-3 text-xl font-bold text-vino">
                <span class="h-px w-10 bg-vino"></span> {{ $nombre }}
                <span class="text-sm font-normal text-neutral-500">({{ $items->count() }})</span>
            </h2>

            <ul class="mt-3 divide-y divide-neutral-200">
                @foreach ($items as $item)
                    <li>
                        <a href="{{ $item['url'] }}" class="block py-3 hover:bg-white">
                            <span class="text-lg text-marino hover:underline">{{ $item['titulo'] }}</span>
                            @if ($item['detalle'])
                                <span class="mt-1 block text-sm text-neutral-600">{{ $item['detalle'] }}</span>
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endforeach
</section>
@endsection
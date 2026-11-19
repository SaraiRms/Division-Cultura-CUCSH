@extends('layouts.public')

@section('content')

{{-- ////////////////////////  BANNER  //////////////////////// --}}
<section class="relative flex h-44 w-full items-center justify-center overflow-hidden bg-marino md:h-56">
    <img src="{{ asset('images/hero/hero-2.jpg') }}" alt=""
         class="absolute inset-0 h-full w-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-marino/70 via-black/55 to-vino/40"></div> {{-- Degradado --}}
        <div class="relative z-10 px-4 text-center text-white">
            <h1 class="text-4xl font-medium tracking-wide md:text-5xl">
                Eventos
            </h1>
        </div>
</section>

<section class="mx-auto max-w-6xl px-6 py-6 mt-4">
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
</section>

<section class="mx-auto max-w-6xl px-4 py-6">
        <p class="mx-auto mt-4 max-w-5xl text-justify leading-relaxed text-marino">
            Conoce los próximos eventos de la División de Estudios de la Cultura. Encuentra conferencias, talleres y encuentros que invitan al diálogo, al aprendizaje y al intercambio de ideas entre nuestra comunidad. ¡Participa y sé parte de estas experiencias!
        </p>
</section>

<section class="mx-auto max-w-6xl px-6 py-12">

    <div class="mt-8 grid gap-10 lg:grid-cols-[2fr_1fr]">

        {{-- ========== CALENDARIO ========== --}}
        <div>
            {{-- Mes y flechas --}}
            <div class="flex items-center justify-between">
                <a href="{{ route('eventos.index', ['mes' => $anterior]) }}" aria-label="Mes anterior"
                   class="rounded-full border border-vino p-2 text-vino hover:bg-vino hover:text-white">
                    <x-heroicon-o-arrow-left class="h-4 w-4" />
                </a>

                <h2 class="text-2xl text-marino">{{ ucfirst($mes->translatedFormat('F Y')) }}</h2>

                <a href="{{ route('eventos.index', ['mes' => $siguiente]) }}" aria-label="Mes siguiente"
                   class="rounded-full border border-vino p-2 text-vino hover:bg-vino hover:text-white">
                    <x-heroicon-o-arrow-right class="h-4 w-4" />
                </a>
            </div>

            {{-- Cuadrícula (solo en pantallas medianas o más grandes) --}}
            <div class="mt-4 hidden grid-cols-7 border-l border-t border-neutral-200 shadow-sm md:grid">
                @foreach (['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'] as $nombre)
                    <div class="border-b border-r border-neutral-200 bg-marino py-2 text-center text-xs tracking-wide text-white">
                        {{ $nombre }}
                    </div>
                @endforeach

                @foreach ($dias as $dia)
                    @php
                        $delMes = $dia->month === $mes->month;
                        $delDia = $porDia->get($dia->format('Y-m-d'), collect());
                    @endphp

                    <div class="min-h-28 border-b border-r border-neutral-200 p-1.5 {{ ! $delMes ? 'bg-neutral-50 text-neutral-400' : ($delDia->isNotEmpty() ? 'bg-vino/5' : 'bg-white') }}">
                        <span class="inline-flex h-6 w-6 items-center justify-center rounded-full text-xs {{ $dia->isToday() ? 'bg-vino text-white' : '' }}">
                            {{ $dia->day }}
                        </span>

                        @foreach ($delDia->take(3) as $e)
                            <a href="{{ route('eventos.show', $e) }}" title="{{ $e->title }}"
                               class="mt-1 block truncate rounded bg-vino/15 font-medium px-1.5 py-0.5 text-[11px] text-vino hover:bg-vino hover:text-white">
                                {{ $e->starts_at->format('H:i') }} {{ $e->title }}
                            </a>
                        @endforeach

                        @if ($delDia->count() > 3)
                            <p class="mt-1 text-[11px] text-neutral-500">+{{ $delDia->count() - 3 }} más</p>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- En celular no cabe la cuadrícula: se muestra una lista del mes --}}
            <ul class="mt-4 divide-y divide-neutral-200 md:hidden">
                @foreach ($eventos as $e)
                    <li>
                        <a href="{{ route('eventos.show', $e) }}" class="flex gap-3 py-3">
                            <div class="bg-neutral-200 px-3 py-2 text-center leading-none">
                                <span class="block text-2xl text-marino">{{ $e->starts_at->format('d') }}</span>
                                <span class="mt-1 block text-xs uppercase text-marino">{{ $e->starts_at->translatedFormat('M') }}</span>
                            </div>
                            <div>
                                <p class="text-marino">{{ $e->title }}</p>
                                <p class="text-xs text-neutral-500">{{ $e->starts_at->format('H:i') }} h</p>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>

            @if ($eventos->isEmpty())
                <p class="mt-4 text-sm text-neutral-500">No hay eventos en este mes.</p>
            @endif
        </div>

        {{-- ========== PRÓXIMOS EVENTOS ========== --}}
        <aside>
            <h2 class="flex items-center gap-3 text-xl font-bold text-vino">
                <span class="h-px w-14 bg-vino"></span> Próximos eventos
            </h2>

            <ul class="mt-4 space-y-4">
                @forelse ($proximos as $e)
                    <li>
                        <a href="{{ route('eventos.show', $e) }}" class="flex gap-3 border-l-4 border-vino bg-white p-3 shadow-sm transition hover:shadow-md">
                            <div class="bg-vino px-3 py-2 text-center leading-none text-white">
                                <span class="block text-2xl">{{ $e->starts_at->format('d') }}</span>
                                <span class="mt-1 block text-xs uppercase">{{ $e->starts_at->translatedFormat('M') }}</span>
                            </div>
                            <div>
                                <p class="text-sm leading-snug text-marino">{{ $e->title }}</p>
                                <p class="mt-1 text-xs text-neutral-500">
                                    {{ $e->starts_at->format('H:i') }} h{{ $e->location ? ' · ' . $e->location : '' }}
                                </p>
                            </div>
                        </a>
                    </li>
                @empty
                    <li class="text-sm text-neutral-500">No hay eventos próximos por ahora.</li>
                @endforelse
            </ul>
        </aside>
    </div>
</section>
@endsection
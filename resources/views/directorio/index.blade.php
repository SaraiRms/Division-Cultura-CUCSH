@extends('layouts.public')

@section('content')

<section class="mx-auto max-w-6xl px-6 py-12">
    <h1 class="flex items-center gap-3 text-3xl text-vino">
        <span class="h-px w-14 bg-vino"></span> Directorio
    </h1>

    {{-- Pestañas --}}
    <div class="mt-6 flex gap-6 border-b border-neutral-200 text-lg">
        <a href="{{ route('directorio.index') }}"
           class="-mb-px border-b-2 pb-2 {{ $vista === 'maestros' ? 'border-vino text-vino' : 'border-transparent text-neutral-500 hover:text-vino' }}">
            Maestros
        </a>
        <a href="{{ route('directorio.index', ['vista' => 'rectoria']) }}"
           class="-mb-px border-b-2 pb-2 {{ $vista === 'rectoria' ? 'border-vino text-vino' : 'border-transparent text-neutral-500 hover:text-vino' }}">
            Rectoría
        </a>
    </div>



    @if ($vista === 'maestros')

        {{-- ========== MAESTROS ========== --}}

        {{-- Filtro por departamento --}}
        <div class="mt-6 flex flex-wrap gap-2">
            <a href="{{ route('directorio.index') }}"
            class="px-3 py-1.5 text-sm {{ ! $departamentoId ? 'bg-vino text-white' : 'bg-neutral-100 text-marino hover:bg-neutral-200' }}">
                Todos
            </a>
            @foreach ($departamentos as $d)
                <a href="{{ route('directorio.index', ['departamento' => $d->id]) }}"
                class="px-3 py-1.5 text-sm {{ $departamentoId === (int) $d->id ? 'bg-vino text-white' : 'bg-neutral-100 text-marino hover:bg-neutral-200' }}">
                    {{ Str::after($d->name, 'Departamento de ') }}
                </a>
            @endforeach
        </div>

        {{-- Título del departamento y conteo --}}
        <div class="mt-8 flex items-baseline justify-between gap-4">
            <h2 class="flex items-center gap-3 text-2xl text-vino">
                <span class="h-px w-10 bg-vino"></span>
                {{ $departamentoActual?->name ?? 'Todos los departamentos' }}
            </h2>
            <p class="shrink-0 text-sm text-neutral-500">
                {{ $maestros->count() === 1 ? '1 maestro' : $maestros->count() . ' maestros' }}
            </p>
        </div>

        @if ($maestros->isEmpty())
            <p class="mt-6 text-neutral-500">No hay maestros registrados en este departamento.</p>
        @else
            <div class="mt-6 grid gap-8 lg:grid-cols-[2fr_3fr]"
                x-data="{
                    seleccionado: {{ $maestros->first()->id }},
                    busqueda: '',
                    coincide(nombre) {
                        const limpiar = t => t.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase();
                        return limpiar(nombre).includes(limpiar(this.busqueda));
                    }
                }">

                {{-- ===== LISTA ===== --}}
                <div>
                    <div class="relative">
                        <x-heroicon-o-magnifying-glass
                            class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-neutral-400" />
                        <input type="search" x-model="busqueda" placeholder="Buscar maestro…"
                            class="w-full border border-neutral-300 bg-white py-2 pl-10 pr-3 text-marino">
                    </div>

                    <div class="mt-4 space-y-3 lg:max-h-[70vh] lg:overflow-y-auto lg:pr-2">
                        @foreach ($maestros as $m)
                            <button type="button"
                                    x-show="coincide(@js($m->full_name))"
                                    @click="seleccionado = {{ $m->id }}; if (window.innerWidth < 1024) $nextTick(() => $refs.detalle.scrollIntoView({ behavior: 'smooth', block: 'start' }))"
                                    :class="seleccionado === {{ $m->id }} ? 'bg-vino' : 'bg-neutral-50 hover:bg-white'"
                                    class="flex w-full items-center gap-4 p-3 text-left shadow-sm transition">

                                <x-foto-persona :path="$m->photo_path" :nombre="$m->full_name" clase="h-16 w-16 rounded-sm" />

                                <div class="min-w-0 flex-1">
                                    <p class="text-lg leading-snug"
                                    :class="seleccionado === {{ $m->id }} ? 'text-white' : 'text-vino'">{{ $m->full_name }}</p>
                                    @if ($m->cargo)
                                        <p class="text-sm"
                                        :class="seleccionado === {{ $m->id }} ? 'text-white/90' : 'text-marino'">{{ $m->cargo }}</p>
                                    @endif
                                </div>

                                <x-heroicon-o-chevron-right class="h-5 w-5 shrink-0 text-neutral-400" />
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- ===== FICHA ===== --}}
                <div x-ref="detalle" class="scroll-mt-6 lg:sticky lg:top-6 lg:self-start">
                    @foreach ($maestros as $m)
                        <div x-cloak x-show="seleccionado === {{ $m->id }}">
                            <x-maestro-detalle :m="$m" />
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    @else


        {{-- ========== RECTORÍA ========== --}}
        <div class="mt-8 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($rectoria as $r)
                <article class="flex gap-4 bg-neutral-50 p-5 shadow-sm">
                    <x-foto-persona :path="$r->photo_path" :nombre="$r->full_name" />

                    <div class="min-w-0">
                        <h2 class="text-lg leading-snug text-vino">{{ $r->full_name }}</h2>
                        @if ($r->cargo)
                            <p class="text-sm text-marino">{{ $r->cargo }}</p>
                        @endif
                        @if ($r->area)
                            <p class="mt-1 text-xs text-neutral-500">{{ $r->area }}</p>
                        @endif
                    </div>
                </article>
            @empty
                <p class="text-neutral-500 md:col-span-2 lg:col-span-3">Aún no hay personas registradas.</p>
            @endforelse
        </div>

    @endif
</section>
@endsection
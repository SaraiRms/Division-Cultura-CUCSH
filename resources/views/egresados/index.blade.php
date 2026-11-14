@extends('layouts.public')

@section('content')
@php
    $tipos = [
        'licenciatura' => 'Licenciaturas',
        'maestria'     => 'Maestrías',
        'doctorado'    => 'Doctorados',
    ];
@endphp

<section class="mx-auto max-w-6xl px-6 py-12"
         x-data="{ depto: {{ $departamentos->first()?->id ?? 'null' }}, foto: null }"
         @keydown.escape.window="foto = null">

    <h1 class="text-4xl text-vino">Egresados</h1>
    <p class="mt-3 max-w-2xl text-marino">
        Generaciones de egresados de licenciatura, maestría y doctorado de cada departamento.
    </p>

    @if ($departamentos->isEmpty())
        <p class="mt-10 text-neutral-500">Próximamente publicaremos las fotografías de nuestros egresados.</p>
    @else
        {{-- Pestañas por departamento --}}
        <div class="mt-8 flex flex-wrap gap-2">
            @foreach ($departamentos as $d)
                <button type="button" @click="depto = {{ $d->id }}"
                        :class="depto === {{ $d->id }} ? 'bg-vino text-white' : 'bg-neutral-100 text-marino hover:bg-neutral-200'"
                        class="px-4 py-2 text-sm transition">
                    {{ $d->name }}
                </button>
            @endforeach
        </div>

        {{-- Contenido de cada departamento --}}
        @foreach ($departamentos as $d)
            <div x-show="depto === {{ $d->id }}" x-cloak class="mt-10 space-y-12">
                @foreach ($tipos as $clave => $titulo)
                    @php $programas = $d->programs->where('type', $clave); @endphp

                    @if ($programas->isNotEmpty())
                        <div>
                            <h2 class="border-b border-vino/30 pb-2 text-2xl text-vino">{{ $titulo }}</h2>

                            @foreach ($programas as $p)
                                <h3 class="mt-6 text-lg text-marino">{{ $p->name }}</h3>

                                <div class="mt-3 grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-4">
                                    @foreach ($p->graduates as $g)
                                        <button type="button"
                                                @click="foto = @js(['src' => asset('storage/' . $g->image_path), 'titulo' => $g->title ?? $p->name])"
                                                class="group block overflow-hidden bg-neutral-50 text-left shadow-sm transition hover:shadow-md">
                                            <img src="{{ asset('storage/' . $g->image_path) }}"
                                                 alt="{{ $g->title ?? $p->name }}"
                                                 loading="lazy"
                                                 class="aspect-[4/3] w-full object-contain bg-neutral-100">
                                            @if ($g->title)
                                                <p class="p-3 text-sm text-marino group-hover:underline">{{ $g->title }}</p>
                                            @endif
                                        </button>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endforeach
            </div>
        @endforeach
    @endif

    {{-- Imagen ampliada --}}
    <div x-show="foto" x-cloak x-transition.opacity @click="foto = null"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 p-4">
        <button type="button" @click="foto = null"
                class="absolute right-4 top-4 text-4xl leading-none text-white" aria-label="Cerrar">&times;</button>
        <figure class="max-w-5xl" @click.stop>
            <img :src="foto?.src" :alt="foto?.titulo" class="max-h-[80vh] w-auto">
            <figcaption class="mt-2 text-center text-white" x-text="foto?.titulo"></figcaption>
        </figure>
    </div>
</section>
@endsection
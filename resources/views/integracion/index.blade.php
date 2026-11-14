@extends('layouts.public')

@section('content')

@php
    $secciones = [
        'alumnos' => [
            'titulo'      => 'Participación Estudiantil',
            'etiqueta'    => 'Estudiantes',
            'descripcion' => 'Comparte tu conocimiento con nuestra comunidad académica, queremos escucharte.',
        ],
        'profesores' => [
            'titulo'      => 'Participación Académica',
            'etiqueta'    => 'Académicos',
            'descripcion' => 'Comparte tus conocimientos, artículos y participaciones docentes con nuestra comunidad académica.',
        ],
    ];

    $seccion = $secciones[$tipo];
@endphp

<section class="mx-auto max-w-6xl px-6 py-12">
    <h1 class="flex items-center gap-3 text-3xl text-vino">
        <span class="h-px w-14 bg-vino"></span> {{ $seccion['titulo'] }}
    </h1>

    {{-- Pestañas --}}
    <div class="mt-6 flex gap-6 border-b border-neutral-200 text-lg">
        @foreach ($secciones as $clave => $s)
            <a href="{{ route('integracion.index', $clave) }}"
               class="-mb-px border-b-2 pb-2 {{ $clave === $tipo ? 'border-vino text-vino' : 'border-transparent text-neutral-500 hover:text-vino' }}">
                {{ $s['etiqueta'] }}
            </a>
        @endforeach
    </div>

    @if (session('enviado'))
        <div role="status" class="mt-6 border-l-4 border-green-600 bg-green-50 p-4 text-green-900">
            ¡Gracias! Recibimos tu participación. El equipo de la División la revisará antes de publicarla.
        </div>
    @endif

    <div class="mt-8 flex flex-col gap-4 bg-neutral-50 p-5 sm:flex-row sm:items-center sm:justify-between">
        <p class="max-w-2xl text-marino">{{ $seccion['descripcion'] }}</p>
        <a href="{{ route('integracion.create', $tipo) }}"
           class="inline-flex shrink-0 items-center gap-2 bg-marino px-6 py-3 text-lg text-white hover:opacity-90">
            Enviar mi participación <x-heroicon-o-arrow-right class="h-5 w-5" />
        </a>
    </div>

    {{-- Lo ya publicado --}}
    <div class="mt-10 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($aportaciones as $a)
            <a href="{{ route('integracion.documento', $a) }}" target="_blank" rel="noopener"
               class="group block overflow-hidden bg-neutral-50 shadow-sm transition hover:shadow-md">
                @if ($a->banner_path)
                    <img src="{{ asset('storage/' . $a->banner_path) }}" alt="{{ $a->title }}"
                         class="aspect-[4/3] w-full object-cover">
                @else
                    <div class="aspect-[4/3] w-full bg-neutral-200"></div>
                @endif

                <div class="p-4">
                    <h2 class="text-lg leading-snug text-marino group-hover:underline">{{ $a->title ?? 'Participación' }}</h2>
                    <p class="mt-1 text-xs text-neutral-500">{{ $a->created_at->translatedFormat('j \d\e F, Y') }}</p>
                    <span class="mt-2 inline-block text-sm text-vino underline">Leer documento (PDF) →</span>
                </div>
            </a>
        @empty
            <p class="text-neutral-500 sm:col-span-2 lg:col-span-3">Aún no hay participaciones publicadas.</p>
        @endforelse
    </div>

    <div class="mt-10">{{ $aportaciones->links() }}</div>
</section>
@endsection
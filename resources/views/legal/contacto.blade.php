@extends('layouts.public')

@section('title', 'Contacto · ' . config('site.nombre'))

@section('content')
@php
    $datos = config('site.contacto_datos', []);
    $hayDatos = collect($datos)->filter()->isNotEmpty();
@endphp

<article class="mx-auto max-w-3xl px-6 py-12 text-marino">

    <h1 class="flex items-center gap-3 text-3xl text-vino">
        <span class="h-px w-14 bg-vino"></span> Contacto
    </h1>

    <p class="mt-6 leading-relaxed">
        ¿Tienes dudas, comentarios o quieres proponer una actividad? Escríbenos a la División de Estudios de la
        Cultura del CUCSH.
    </p>

    @if ($hayDatos)
        <dl class="mt-8 space-y-6">
            @if (! empty($datos['correo']))
                <div class="flex items-start gap-4">
                    <x-heroicon-o-envelope class="h-6 w-6 shrink-0 text-vino" aria-hidden="true" />
                    <div>
                        <dt class="text-sm text-neutral-500">Correo electrónico</dt>
                        <dd><a href="mailto:{{ $datos['correo'] }}" class="text-vino underline">{{ $datos['correo'] }}</a></dd>
                    </div>
                </div>
            @endif

            @if (! empty($datos['telefono']))
                <div class="flex items-start gap-4">
                    <x-heroicon-o-phone class="h-6 w-6 shrink-0 text-vino" aria-hidden="true" />
                    <div>
                        <dt class="text-sm text-neutral-500">Teléfono</dt>
                        <dd>{{ $datos['telefono'] }}</dd>
                    </div>
                </div>
            @endif

            @if (! empty($datos['direccion']))
                <div class="flex items-start gap-4">
                    <x-heroicon-o-map-pin class="h-6 w-6 shrink-0 text-vino" aria-hidden="true" />
                    <div>
                        <dt class="text-sm text-neutral-500">Dirección</dt>
                        <dd>{{ $datos['direccion'] }}</dd>
                    </div>
                </div>
            @endif

            @if (! empty($datos['horario']))
                <div class="flex items-start gap-4">
                    <x-heroicon-o-clock class="h-6 w-6 shrink-0 text-vino" aria-hidden="true" />
                    <div>
                        <dt class="text-sm text-neutral-500">Horario de atención</dt>
                        <dd>{{ $datos['horario'] }}</dd>
                    </div>
                </div>
            @endif
        </dl>
    @endif

    {{-- Redes sociales (las que tengan enlace) --}}
    <h2 class="mt-12 text-xl text-vino">También puedes encontrarnos en</h2>
    <ul class="mt-3 flex flex-wrap gap-x-6 gap-y-2">
        @foreach (config('site.redes', []) as $nombre => $enlace)
            @if ($enlace && $enlace !== '#')
                <li><a href="{{ $enlace }}" target="_blank" rel="noopener" class="text-vino underline">{{ $nombre }}</a></li>
            @endif
        @endforeach
    </ul>

    <p class="mt-12 text-sm text-neutral-500">
        Para solicitudes sobre tus datos personales, consulta el
        <a href="{{ route('legal.privacidad') }}" class="text-vino underline">Aviso de privacidad</a>.
    </p>
</article>
@endsection

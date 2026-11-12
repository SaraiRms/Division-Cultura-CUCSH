@extends('layouts.public')

@section('content')

{{-- Cada tipo de programa: su nombre y su color --}}
@php
    $tipos = [
        'licenciatura' => ['Licenciaturas', 'bg-vino'],
        'maestria'     => ['Maestrías',     'bg-marino'],
        'doctorado'    => ['Doctorados',    'bg-[#c2966a]'],
        'centro'       => ['Centros',       'bg-neutral-600'],
    ];
@endphp

{{-- ========== BANNER ========== --}}
<section id="presentación" class="scroll-mt-24 bg-neutral-100">

    <div class="relative flex h-64 w-full items-center justify-center bg-gray-800 bg-cover bg-center" style="background-image: url(images/edf1.jpg)">
        <div class="absolute inset-0 bg-black/50"></div>
        <h1 class="relative z-10 px-4 text-center text-4xl tracking-wide text-white md:text-7xl">
            División de Estudios de la Cultura
        </h1>
    </div>

</section>


{{-- ========== PRESENTACIÓN Y OBJETIVOS ========== --}}
<section id="presentacion" class="scroll-mt-24 bg-neutral-100">
    <div class="mx-auto max-w-6xl  px-6 py-14 space-y-12">
        <div>
            <h1 class="flex items-center gap-3 text-4xl text-vino">
                <span class="h-px w-14 bg-vino"></span> Presentación
            </h1>
            <div class="contenido mt-5">
                <p>La División de Estudios de la Cultura fue creada a partir de la Reforma Universitaria de 1994, 
                    cuando la Universidad de Guadalajara se convirtió en una Red Universitaria. <br>
                    Según el artículo 23 de la Ley Orgánica de la Universidad, la Red se integrará por: 
                    Los Centros Universitarios; el Sistema de Educación Media Superior, y la Administración General de la Universidad. 
                    A su vez los Centros Universitarios estarán integrados por Divisiones y Departamentos.
                    El Centro Universitario de Ciencias Sociales y Humanidades está conformado por cinco Divisiones, 
                    tres de ellas de Estudios Básicos y dos de Estudios Especializados.
                    Estudios Básicos: División de Estudios Políticos y Sociales, División de Estudios Jurídicos, y División de Estudios 
                    Históricos y Humanos. <br>
                    Estudios Especializados: División de Estudios de la Cultura y División de Estudios de Estado y Sociedad.
                    La División de Estudios de la Cultura es una estructura académico-administrativa que agrupa departamentos articulados 
                    en torno a ciertos rasgos de afinidad académica. Esta se ubica dentro del campo de los estudios especializados y a 
                    ella están adscritos cinco departamentos y un centro de investigación."</p>

                @if ($division?->description)
                    {!! $division->description !!}
                @endif
            </div>
        </div>

        <div>
            <h2 class="flex items-center gap-3 text-4xl text-vino">
                <span class="h-px w-14 bg-vino"></span> Objetivos
            </h2>
            <ul class="contenido mt-5">
                <li>La División centra sus funciones en las gestiones administrativas de enlace coordinando la relación entre los 
                    departamentos de estudios especializados que dependen de ésta y la Rectoría del Centro Universitario de 
                    Ciencias Sociales y Humanidades.</li>
                <li>Su propósito principal se orienta a impulsar el apoyo a los departamentos para garantizar el cumplimiento de 
                    las metas y objetivos de los proyectos que desarrollan en sus funciones sustantivas.</li>
                <li>Aportar propuestas de solución a problemas que demanden los procesos de gestión fundamentándose en las normas 
                    dictadas por las instituciones y las reglas estructuradas en las mismas, proporcionar información y fomentar 
                    la comunicación interdepartamental.</li>
            </ul>
        </div>
    </div>
</section>


{{-- ========== DEPARTAMENTOS ========== --}}
<section id="departamentos" class="mx-auto max-w-6xl scroll-mt-24 px-6 py-14">
    <h2 class="flex items-center gap-3 text-3xl text-vino">
        <span class="h-px w-14 bg-vino"></span> Departamentos
    </h2>

    {{-- Leyenda de colores --}}
    <div class="mt-4 flex flex-wrap gap-x-5 gap-y-1 text-xs text-neutral-600">
        @foreach ($tipos as [$etiqueta, $color])
            <span class="flex items-center gap-2">
                <span class="h-2.5 w-2.5 {{ $color }}"></span> {{ $etiqueta }}
            </span>
        @endforeach
    </div>

    <div class="mt-8 grid gap-8 md:grid-cols-2">
        @forelse ($departamentos as $d)
            <article class="flex flex-col overflow-hidden bg-neutral-50 shadow-sm">
                @if ($d->image_path)
                    <img src="{{ asset('storage/' . $d->image_path) }}" alt="{{ $d->name }}"
                         class="h-48 w-full object-cover">
                @else
                    <div class="h-48 w-full bg-neutral-200"></div>
                @endif

                <div class="flex flex-1 flex-col p-6">
                    <h3 class="text-2xl leading-snug text-vino">{{ $d->name }}</h3>

                    @if ($d->description)
                        <div class="contenido mt-2 text-sm">{!! $d->description !!}</div>
                    @endif

                    {{-- Programas agrupados por tipo --}}
                    @foreach ($tipos as $clave => [$etiqueta, $color])
                        @php $lista = $d->programs->where('type', $clave); @endphp {{-- Aquí se hace la separación automatica --}} 

                        @if ($lista->isNotEmpty())
                            <div class="mt-5">
                                <p class="flex items-center gap-2 text-xs uppercase tracking-widest text-neutral-500">
                                    <span class="h-2.5 w-2.5 {{ $color }}"></span> {{ $etiqueta }}
                                </p>
                                <ul class="mt-1 space-y-1 text-marino">
                                    @foreach ($lista as $p)
                                        <li>{{ $p->name }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    @endforeach

                    <a href="{{ config('site.departamentos_enlaces.' . $d->name, '#') }}"
                       target="_blank" rel="noopener"
                       class="mt-auto pt-6 text-sm text-vino underline">Conocer más →</a>
                </div>
            </article>
        @empty
            <p class="text-neutral-500 md:col-span-2">Aún no hay departamentos registrados.</p>
        @endforelse
    </div>
</section>
@endsection
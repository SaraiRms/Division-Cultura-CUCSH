@props(['m'])

@php
    $nombresTabs = [
        'curriculum' => 'Currículum',
        'formacion'  => 'Formación',
        'materias'   => 'Materias',
    ];

    // Solo se muestran las pestañas que tienen algo que mostrar
    $tabs = collect([
        'curriculum' => $m->cv_summary || $m->cv_path,
        'formacion'  => (bool) $m->nivel_academico,
        'materias'   => $m->area_conocimiento || $m->materias,
    ])->filter()->keys();
@endphp

<article class="bg-neutral-50 p-6 shadow-sm md:p-8">

    {{-- Encabezado: foto y datos principales --}}
    <div class="flex flex-col gap-6 sm:flex-row">
        <x-foto-persona :path="$m->photo_path" :nombre="$m->full_name" clase="h-56 w-44" />

        <div class="min-w-0">
            <h2 class="text-3xl leading-tight text-vino">{{ $m->full_name }}</h2>

            @if ($m->cargo)
                <p class="mt-3 flex items-center gap-2 text-marino">
                    <x-heroicon-o-user class="h-5 w-5 shrink-0 text-vino" /> {{ $m->cargo }}
                </p>
            @endif

            @if ($m->area_conocimiento)
                <p class="mt-2 flex items-center gap-2 text-marino">
                    <x-heroicon-o-light-bulb class="h-5 w-5 shrink-0 text-vino" /> {{ $m->area_conocimiento }}
                </p>
            @endif

            <div class="mt-3 flex flex-wrap gap-1">
                @foreach ($m->departments as $d)
                    <span class="bg-vino/10 px-2 py-0.5 text-[11px] text-vino">
                        {{ Str::after($d->name, 'Departamento de ') }}
                    </span>
                @endforeach
            </div>

            <ul class="mt-4 space-y-1 text-sm text-neutral-600">
                @if ($m->email)
                    <li class="flex items-center gap-2 break-all">
                        <x-heroicon-o-envelope class="h-4 w-4 shrink-0 text-vino" />
                        <a href="mailto:{{ $m->email }}" class="hover:underline">{{ $m->email }}</a>
                    </li>
                @endif
                @if ($m->phone || $m->phone_ext)
                    <li class="flex items-center gap-2">
                        <x-heroicon-o-phone class="h-4 w-4 shrink-0 text-vino" />
                        {{ trim($m->phone . ($m->phone_ext ? ' ext. ' . $m->phone_ext : '')) }}
                    </li>
                @endif
            </ul>
        </div>
    </div>

    {{-- Pestañas --}}
    @if ($tabs->isNotEmpty())
        <div x-data="{ tab: '{{ $tabs->first() }}' }" class="mt-8">

            <div class="flex flex-wrap gap-x-6 border-b border-neutral-200">
                @foreach ($tabs as $t)
                    <button type="button" @click="tab = '{{ $t }}'"
                            :class="tab === '{{ $t }}' ? 'border-vino text-vino' : 'border-transparent text-neutral-500 hover:text-vino'"
                            class="-mb-px border-b-2 pb-2 text-lg">
                        {{ $nombresTabs[$t] }}
                    </button>
                @endforeach
            </div>

            {{-- Currículum --}}
            @if ($tabs->contains('curriculum'))
                <div x-show="tab === 'curriculum'" class="mt-6">
                    @if ($m->cv_summary)
                        <h3 class="flex items-center gap-3 text-xl text-vino">
                            <span class="h-px w-8 bg-vino"></span> Resumen curricular
                        </h3>

                        {{-- Acepta texto simple o texto con formato del editor --}}
                        @if ($m->cv_summary !== strip_tags($m->cv_summary))
                            <div class="contenido mt-3">{!! $m->cv_summary !!}</div>
                        @else
                            <p class="mt-3 whitespace-pre-line leading-relaxed">{{ $m->cv_summary }}</p>
                        @endif
                    @endif

                    @if ($m->cv_path)
                        <a href="{{ asset('storage/' . $m->cv_path) }}" target="_blank" rel="noopener"
                           class="mt-5 inline-flex items-center gap-2 bg-marino px-4 py-2 text-white hover:opacity-90">
                            <x-heroicon-o-document-arrow-down class="h-5 w-5" /> Descargar CV (PDF)
                        </a>
                    @endif
                </div>
            @endif

            {{-- Formación --}}
            @if ($tabs->contains('formacion'))
                <div x-show="tab === 'formacion'" class="mt-6">
                    <h3 class="flex items-center gap-3 text-xl text-vino">
                        <span class="h-px w-8 bg-vino"></span> Nivel académico
                    </h3>
                    <p class="mt-3">{{ $m->nivel_academico }}</p>
                </div>
            @endif

            {{-- Áreas y materias --}}
            @if ($tabs->contains('materias'))
                <div x-show="tab === 'materias'" class="mt-6 space-y-6">
                    @if ($m->area_conocimiento)
                        <div>
                            <h3 class="flex items-center gap-3 text-xl text-vino">
                                <span class="h-px w-8 bg-vino"></span> Área de conocimiento
                            </h3>
                            <p class="mt-3">{{ $m->area_conocimiento }}</p>
                        </div>
                    @endif

                    @if ($m->materias)
                        <div>
                            <h3 class="flex items-center gap-3 text-xl text-vino">
                                <span class="h-px w-8 bg-vino"></span> Materias impartidas
                            </h3>
                            <p class="mt-3 whitespace-pre-line">{{ $m->materias }}</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    @endif
</article>
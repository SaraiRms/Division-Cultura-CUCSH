<!DOCTYPE html>
<html lang="es">
<head>
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('site.nombre'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

</head>

<body class="flex min-h-screen flex-col bg-crema font-sans text-marino antialiased">

    {{-- Encabezado --}}
    @php
        $enlaces = [
            ['Inicio', url('/'), request()->is('/')],
            ['Podcast', url('/podcast'), request()->is('podcast*')],
            ['Conversatorio', url('/conversatorios'), request()->is('conversatorios*')],
            ['Participación Académica', url('/integracion'), request()->is('integracion*')],
            ['Directorio', url('/directorio'), request()->is('directorio*')],
            ['Egresados', url('/egresados'), request()->is('egresados*')],
        ];
        $textoAcceso = auth()->check() ? 'Administrar' : 'Iniciar sesión';
    @endphp

    <header class="bg-marino" x-data="{ abierto: false }">

        <div class="flex items-stretch">
            <a href="{{ url('/') }}" class="flex items-center gap-3 rounded-br-[2.5rem] bg-crema px-4 py-3 lg:gap-4 lg:px-6 lg:py-4">
                <img src="{{ asset('images/cucsh_dual.svg') }}" alt="CUCSH" class="h-9 lg:h-12">
                <span class="h-8 w-px bg-marino/20 lg:h-10"></span>
                <img src="{{ asset('images/Logo_Div_Cultura.svg') }}" alt="{{ config('site.nombre') }}" class="h-9 lg:h-12">
            </a>

            {{-- Botón de hamburguesa (solo celular y tablet) --}}
            <button type="button" @click="abierto = !abierto" aria-label="Menú"
                    class="ml-auto px-5 text-white lg:hidden">
                <x-heroicon-o-bars-3 x-show="!abierto" class="h-7 w-7" />
                <x-heroicon-o-x-mark x-show="abierto" x-cloak class="h-7 w-7" />
            </button>

            {{-- Menú en pantalla grande --}}
            <nav class="hidden flex-1 flex-wrap items-center justify-end gap-x-8 gap-y-2 px-6 text-white lg:flex">
                @foreach ($enlaces as [$texto, $url, $activo])
                    <a href="{{ $url }}"
                       class="border-b-2 py-1 {{ $activo ? 'border-white' : 'border-transparent hover:border-white/50' }}">
                        {{ $texto }}
                    </a>
                @endforeach

                <a href="{{ url('/admin') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-crema px-4 py-2 text-sm font-semibold text-marino transition duration-200 hover:bg-white hover:shadow-md
                            focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-white">
                        <x-heroicon-o-cog-6-tooth class="h-4 w-4" aria-hidden="true" />
                        <span>{{ $textoAcceso }}</span>
                </a>
            </nav>
        </div>

        {{-- Menú desplegable en celular y tablet --}}
        <nav x-show="abierto" x-cloak x-transition class="flex flex-col px-6 pb-4 mt-4 text-white lg:hidden">
            @foreach ($enlaces as [$texto, $url, $activo])
                <a href="{{ $url }}"
                   class="border-b border-white/10 py-3 {{ $activo ? 'font-semibold' : '' }}">
                    {{ $texto }}
                </a>
            @endforeach

            <a href="{{ url('/admin') }}"
            class="mt-4 inline-flex items-center justify-center gap-2 rounded-lg
                    bg-crema px-4 py-2 text-sm font-semibold text-marino
                    transition duration-200
                    hover:bg-white hover:shadow-md
                    focus-visible:outline focus-visible:outline-2
                    focus-visible:outline-offset-4 focus-visible:outline-white">

                <x-heroicon-o-cog-6-tooth class="h-4 w-4" aria-hidden="true" />

                <span>{{ $textoAcceso }}</span>
            </a>
        </nav>
    </header>

    <main class="flex-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="mt-16 bg-vino text-white">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center gap-8 px-6 py-8">
            <div class="flex items-center gap-4">
                <img src="{{ asset('images/udg_w.svg') }}" alt="udg" class="h-15">
                <span class="h-10 w-px bg-white/30"></span>
                <img src="{{ asset('images/Logo_Div_Cultura.svg') }}" alt="{{ config('site.nombre') }}"
                     class="h-15 brightness-0 invert">
            </div>

            <ul class="flex flex-wrap gap-x-6 gap-y-1 text-sm">
                <li><a href="{{ route('legal.privacidad') }}" class="hover:underline">Aviso de privacidad</a></li>
                <li><a href="{{ route('legal.terminos') }}" class="hover:underline">Términos y condiciones</a></li>
                <li><a href="{{ route('legal.contacto') }}" class="hover:underline">Contacto</a></li>
            </ul>

            <div class="ml-auto flex items-center gap-4 text-sm">
                <span>Síguenos:</span>
                @foreach (config('site.redes', []) as $nombre => $enlace)
                    @if ($enlace && $enlace !== '#')
                        <a href="{{ $enlace }}" target="_blank" rel="noopener"
                           aria-label="{{ $nombre }}" title="{{ $nombre }}"
                           class="flex h-9 w-9 items-center justify-center transition hover:scale-110 hover:opacity-80">
                            <img src="{{ asset('images/redes/' . strtolower($nombre) . '.png') }}"
                                 alt="{{ $nombre }}" class="h-full w-full object-contain">
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </footer>

</body>
</html>
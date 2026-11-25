<!DOCTYPE html>
<html lang="es" class="js">
<head>
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('site.nombre'))</title>
    <meta name="description" content="@yield('description', 'Sitio de la División de Estudios de la Cultura del CUCSH, Universidad de Guadalajara: noticias, eventos, podcast, conversatorios, egresados y más.')">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

</head>

<body class="flex min-h-screen flex-col bg-crema font-sans text-marino">

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
    @endphp

    <header class="site-header sticky top-0 z-50 border-b border-white/10 text-white" x-data="{ abierto: false, bajo: false, buscando: false }" x-init="bajo = window.scrollY > 8"
        @scroll.window.passive="bajo = window.scrollY > 8" :class="{ 'con-sombra': bajo }" @keydown.escape.stop="if (abierto) { abierto = false; $refs.botonMenu.focus(); } buscando = false" @click.outside="abierto = false; buscando = false">
        <div class="mx-auto flex max-w-screen-2xl items-center justify-between gap-4">
            {{-- Identidad institucional --}}
            <a href="{{ url('/') }}" aria-label="CUCSH Cultura — Inicio" 
                class="flex min-w-0 items-center gap-2 rounded-br-3xl bg-crema px-2 py-4 sm:gap-4 sm:px-6
                focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-[-4px] focus-visible:outline-marino">
                <img src="{{ asset('images/cucsh_dual.svg') }}" alt="CUCSH" class="h-7 w-auto min-w-0 object-contain sm:h-10">
                <span aria-hidden="true" class="h-7 w-px shrink-0 bg-marino/20 sm:h-8"></span>
                <img src="{{ asset('images/Logo_Div_Cultura.svg') }}" alt="{{ config('site.nombre') }}" class="h-7 w-auto min-w-0 object-contain object-left sm:h-10">
            </a>

            {{-- Navegación de escritorio --}}
            <nav aria-label="Navegación principal" class="hidden items-center gap-1 pr-6 min-[1440px]:flex">
                @foreach ($enlaces as [$texto, $url, $activo])
                    <a href="{{ $url }}" @if ($activo) aria-current="page" @endif class="rounded-lg px-3 py-3 text-sm font-medium whitespace-nowrap transition-colors duration-150 hover:bg-white/10
                        focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white {{ $activo ? 'bg-white/10 text-white' : 'text-slate-200' }}">
                        {{ $texto }}
                    </a>
                @endforeach
            </nav>

            {{-- Botón de búsqueda --}}
            <button type="button"
                    @click="buscando = !buscando; if (buscando) $nextTick(() => $refs.campoBusqueda.focus())"
                    :aria-expanded="buscando.toString()" aria-controls="barra-busqueda" aria-label="Buscar en el sitio"
                    class="ml-auto mr-1 inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-lg transition-colors hover:bg-white/10
                        focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white min-[1440px]:ml-0 min-[1440px]:mr-6">
                <x-heroicon-o-magnifying-glass class="h-6 w-6" aria-hidden="true" />
            </button>

            {{-- Botón del menú móvil --}}
            <button x-ref="botonMenu" type="button" @click="abierto = !abierto" :aria-expanded="abierto.toString()" aria-controls="navegacion-movil"
                :aria-label="abierto ? 'Cerrar menú' : 'Abrir menú'" class="mr-3 inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-lg transition-colors hover:bg-white/10
                focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white min-[1440px]:hidden">
                <x-heroicon-o-bars-3 x-show="!abierto" class="h-6 w-6" aria-hidden="true"/>
                <x-heroicon-o-x-mark x-show="abierto" x-cloak class="h-6 w-6" aria-hidden="true"/>
            </button>
        </div>

        {{-- Barra de búsqueda --}}
        <div id="barra-busqueda" x-show="buscando" x-cloak x-transition.opacity class="border-t border-white/10 bg-marino px-4 py-4">
            <form action="{{ route('buscar') }}" method="GET" role="search" class="mx-auto flex max-w-3xl gap-2">
                <label for="busqueda-global" class="sr-only">Buscar en el sitio</label>
                <input id="busqueda-global" x-ref="campoBusqueda" type="search" name="q" required minlength="2" maxlength="100"
                    placeholder="Buscar noticias, eventos, personas…"
                    class="w-full rounded-lg bg-white px-4 py-3 text-marino">
                <button type="submit" class="rounded-lg bg-vino px-5 py-3 font-semibold text-white hover:bg-vino/90">Buscar</button>
            </form>
        </div>

        {{-- Panel móvil con desplazamiento propio --}}
        <nav id="navegacion-movil" aria-label="Navegación principal móvil" x-show="abierto" x-cloak class="site-mobile-nav overflow-y-auto border-t border-white/10 bg-marino px-4 py-4 min-[1440px]:hidden">
            <div class="flex flex-col gap-1">
                @foreach ($enlaces as [$texto, $url, $activo])
                    <a href="{{ $url }}" @if ($activo) aria-current="page" @endif
                        class="flex min-h-11 items-center rounded-lg px-4 py-3
                            text-base transition-colors hover:bg-white/10
                            focus-visible:outline focus-visible:outline-2
                            focus-visible:outline-white
                            {{ $activo ? 'bg-white/10 font-semibold text-white' : 'text-slate-200' }}"
                    >
                        {{ $texto }}
                    </a>
                @endforeach
            </div>
        </nav>
    </header>

    <main class="flex-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    @php
        // Todas las páginas del sitio (el menú de arriba lleva solo las principales).
        $enlacesPie = array_merge($enlaces, [
            ['Noticias', route('noticias.index'), request()->is('noticias*')],
            ['Eventos', route('eventos.index'), request()->is('eventos*')],
            ['Departamentos', route('departamentos.index'), request()->is('departamentos*')],
        ]);
    @endphp

    <footer class="mt-16 border-t-4 py-2 border-marino/15 bg-vino text-white">
        <div class="mx-auto max-w-7xl px-6 pb-8 pt-12 lg:pt-16">

            <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-[1.5fr_1fr_1fr]">

                {{-- Identidad institucional --}}
                <div>
                    <div class="flex flex-wrap items-center gap-5">
                        <img
                            src="{{ asset('images/udg_w.svg') }}"
                            alt="Universidad de Guadalajara"
                            class="h-14 w-auto max-w-full object-contain"
                        >

                        <span
                            aria-hidden="true"
                            class="hidden h-12 w-px bg-white/25 sm:block"
                        ></span>

                        <img
                            src="{{ asset('images/Logo_Div_Cultura.svg') }}"
                            alt="{{ config('site.nombre') }}"
                            class="h-12 w-auto max-w-full object-contain brightness-0 invert"
                        >
                    </div>

                    <p class="mt-6 max-w-sm text-sm leading-7 text-white/75">
                        Un espacio para compartir la cultura, las ideas
                        y las voces de nuestra comunidad universitaria.
                    </p>
                </div>

                {{-- Explorar --}}
                <nav aria-label="Navegación del pie de página">
                    <h2 class="text-xs font-semibold uppercase tracking-widest text-white/60">
                        Explora
                    </h2>

                    <ul class="mt-5 grid grid-cols-2 gap-x-6 gap-y-3 text-sm">
                        @foreach ($enlacesPie as [$texto, $url, $activo])
                            <li>
                                <a
                                    href="{{ $url }}"
                                    @if ($activo) aria-current="page" @endif
                                    class="inline-block py-2 text-white/80
                                        transition-colors duration-200
                                        hover:text-white hover:underline
                                        underline-offset-4
                                        focus-visible:outline
                                        focus-visible:outline-2
                                        focus-visible:outline-offset-4
                                        focus-visible:outline-white"
                                >
                                    {{ $texto }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </nav>

                {{-- Contacto y redes --}}
                <div>
                    <h2 class="text-xs font-semibold uppercase tracking-widest text-white/60">
                        Sigamos en contacto
                    </h2>

                    <p class="mt-5 max-w-xs text-sm leading-7 text-white/75">
                        Conoce nuestras actividades y sigue las conversaciones
                        de nuestra comunidad.
                    </p>

                    <a
                        href="{{ route('legal.contacto') }}"
                        class="mt-4 inline-flex items-center gap-2 text-sm
                            font-semibold underline underline-offset-4
                            transition-colors hover:text-white/75"
                    >
                        Contáctanos
                        <span aria-hidden="true">→</span>
                    </a>

                    <div class="mt-6 flex flex-wrap gap-3">
                        @foreach (config('site.redes', []) as $nombre => $enlace)
                            @if ($enlace && $enlace !== '#')
                                <a
                                    href="{{ $enlace }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    aria-label="{{ $nombre }} (abre en una pestaña nueva)"
                                    title="{{ $nombre }}"
                                    class="flex h-11 w-11 items-center justify-center
                                        rounded-full border border-white/20
                                        bg-white/5 transition duration-200
                                        hover:-translate-y-1 hover:border-white/50
                                        hover:bg-white/15
                                        motion-reduce:transform-none
                                        motion-reduce:transition-none
                                        focus-visible:outline
                                        focus-visible:outline-2
                                        focus-visible:outline-offset-4
                                        focus-visible:outline-white"
                                >
                                    <img
                                        src="{{ asset('images/redes/' . strtolower($nombre) . '.png') }}"
                                        alt=""
                                        class="h-5 w-5 object-contain"
                                        loading="lazy"
                                    >
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Cierre legal --}}
            <div class="mt-12 flex flex-col gap-4 border-t border-white/20 pt-6
                        text-xs leading-6 text-white/65
                        md:flex-row md:items-center md:justify-between">

                <p>
                    © {{ now()->year }} Universidad de Guadalajara
                    <span class="mx-1" aria-hidden="true">·</span>
                    División de Estudios de la Cultura
                </p>

                <ul class="flex flex-wrap gap-x-6 gap-y-2">
                    <li>
                        <a
                            href="{{ route('legal.privacidad') }}"
                            class="transition-colors hover:text-white hover:underline underline-offset-4"
                        >
                            Aviso de privacidad
                        </a>
                    </li>

                    <li>
                        <a
                            href="{{ route('legal.terminos') }}"
                            class="transition-colors hover:text-white hover:underline underline-offset-4"
                        >
                            Términos y condiciones
                        </a>
                    </li>
                </ul>
            </div>

        </div>
    </footer>

</body>
</html>
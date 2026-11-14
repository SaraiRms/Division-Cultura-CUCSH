<!DOCTYPE html>
<html lang="es">
<head>
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('site.nombre'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

</head>

<body class="bg-crema font-sans text-marino antialiased">

    {{-- Encabezado --}}
    <header class="bg-marino">

        <div class="flex items-stretch">
            <a href="{{ url('/') }}" class="flex items-center gap-4 rounded-br-[2.5rem] bg-crema px-6 py-4">
                <img src="{{ asset('images/cucsh_dual.svg') }}" alt="CUCSH" class="h-12">
                <span class="h-10 w-px bg-marino/20"></span>
                <img src="{{ asset('images/Logo_Div_Cultura.svg') }}" alt="{{ config('site.nombre') }}" class="h-12">
            </a>

            <nav class="flex flex-1 flex-wrap items-center justify-end gap-x-8 gap-y-2 px-6 text-white">
                @php
                    $enlaces = [
                        ['Inicio', url('/'), request()->is('/')],
                        ['Podcast', url('/podcast'), request()->is('podcast*')],
                        ['Conversatorio', url('/conversatorios'), request()->is('conversatorios*')],
                        ['Participación Académica', url('/integracion'), request()->is('integracion*')],
                        ['Directorio', url('/directorio'), request()->is('directorio*')],
                        ['Egresados', url('/egresados'), request()->is('egresados*')]
                    ];
                @endphp


                {{-- menú --}}
                
                @foreach ($enlaces as [$texto, $url, $activo])
                    <a href="{{ $url }}"
                       class="border-b-2 py-1 {{ $activo ? 'border-white' : 'border-transparent hover:border-white/50' }}">
                        {{ $texto }}
                    </a>
                @endforeach

                {{-- Quien ya inició sesión ve un acceso al panel --}}
                {{-- @auth
                    <a href="{{ url('/admin') }}" class="rounded-full bg-white/15 px-4 py-1 text-sm hover:bg-white/25">
                        {{ auth()->user()->name }} · Administrar
                    </a>
                @endauth
                 --}}
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="mt-16 bg-vino text-white">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center gap-8 px-6 py-8">
            <div class="flex items-center gap-4">
                <span class="text-lg uppercase tracking-wide">Universidad de Guadalajara</span>
                <span class="h-10 w-px bg-white/30"></span>
                <img src="{{ asset('images/Logo_Div_Cultura.svg') }}" alt="{{ config('site.nombre') }}"
                     class="h-12 brightness-0 invert">
            </div>

            <ul class="flex flex-wrap gap-x-6 gap-y-1 text-sm">
                <li><a href="{{ config('site.aviso_privacidad') }}" class="hover:underline">Aviso de privacidad</a></li>
                <li><a href="{{ config('site.terminos') }}" class="hover:underline">Términos y condiciones</a></li>
                <li><a href="{{ config('site.contacto') }}" class="hover:underline">Contacto</a></li>
            </ul>

            <div class="ml-auto flex flex-wrap items-center gap-3 text-sm">
                <span>Síguenos:</span>
                @foreach (config('site.redes', []) as $nombre => $enlace)
                    <a href="{{ $enlace }}" class="rounded-full border border-white/60 px-3 py-1 hover:bg-white/15">{{ $nombre }}</a>
                @endforeach
            </div>
        </div>
    </footer>

</body>
</html>
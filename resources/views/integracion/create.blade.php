@extends('layouts.public')

@section('content')
<section class="mx-auto max-w-2xl px-6 py-12">
    <a href="{{ route('integracion.index', $tipo) }}" class="text-sm text-vino underline">← Volver</a>

    <h1 class="mt-4 text-3xl text-vino">Enviar mi participación</h1>
    <p class="mt-3 text-marino">
        Adjunta tu documento en PDF. El equipo de la División lo revisará antes de publicarlo.
    </p>

    <form method="POST" action="{{ route('integracion.store', $tipo) }}" enctype="multipart/form-data"
          x-data="{ enviando: false }" @submit="enviando = true" class="mt-8 space-y-6">
        @csrf

        {{-- Trampa para robots: las personas no lo ven --}}
        <div style="position:absolute; left:-9999px;" aria-hidden="true">
            <label>Sitio web <input type="text" name="sitio_web" tabindex="-1" autocomplete="off"></label>
        </div>

        {{-- <div>
            <label for="title" class="text-xl">Título<span class="text-vino">*</span></label>
            <textarea name="title" id="title" rows="2" maxlength="200"></textarea>
            <p class="mt-1 text-xs text-neutral-500">Coloca el titulo de tu investigación, documento, etc</p>
        </div> --}}

        <div>
            <label for="contact_email" class="block text-marino">Correo de contacto <span class="text-vino">*</span></label>
            <input id="contact_email" name="contact_email" type="email" required
                   value="{{ old('contact_email') }}"
                   class="mt-1 w-full border border-neutral-300 bg-white px-3 py-2 text-marino">
            <p class="mt-1 text-xs text-neutral-500">Solo lo usará la División para contactarte. No se publica.</p>
            @error('contact_email') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="comentarios" class="block text-marino">Comentarios (opcional)</label>
            <textarea id="comentarios" name="comentarios" rows="4" maxlength="1000"
                      class="mt-1 w-full border border-neutral-300 bg-white px-3 py-2 text-marino">{{ old('comentarios') }}</textarea>
            @error('comentarios') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="pdf" class="block text-marino">Documento en PDF <span class="text-vino">*</span></label>
            <input id="pdf" name="pdf" type="file" accept="application/pdf,.pdf" required
                   class="mt-1 block w-full border border-neutral-300 bg-white p-2 text-sm text-marino file:mr-4 file:border-0 file:bg-marino file:px-4 file:py-2 file:text-white">
            <p class="mt-1 text-xs text-neutral-500">Solo PDF. Tamaño máximo: 5 MB.</p>
            @error('pdf') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="flex items-start gap-3 text-sm text-marino">
                <input type="checkbox" name="acepto" value="1" required @checked(old('acepto')) class="mt-1">
                <span>
                    He leído y acepto el
                    <a href="{{ config('site.aviso_privacidad', '#') }}" target="_blank" rel="noopener"
                       class="text-vino underline">aviso de privacidad</a>.
                </span>
            </label>
            @error('acepto') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>

        <button type="submit" :disabled="enviando"
                class="bg-marino px-6 py-3 text-lg text-white hover:opacity-90 disabled:opacity-50">
            <span x-show="! enviando">Enviar participación</span>
            <span x-show="enviando" x-cloak>Enviando…</span>
        </button>
    </form>
</section>
@endsection
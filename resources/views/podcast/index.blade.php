@extends('layouts.public')

@section('content')

{{-- ////////////////////////  BANNER CON VIDEO  //////////////////////// --}}
<section class="relative flex h-44 w-full items-center justify-center overflow-hidden bg-marino md:h-56">
    <video autoplay muted loop playsinline
            poster="{{ asset('images/podcast.png') }}"
            class="absolute inset-0 h-full w-full object-cover motion-reduce:hidden">
            <source src="{{ asset('videos/vid_pod.mp4') }}" type="video/mp4">
    </video>
    {{-- Imagen de respaldo si el usuario prefiere movimiento reducido --}}
    <img src="{{ asset('images/podcast.png') }}" alt=""
        class="hidden h-full w-full object-cover motion-reduce:block">
    <div class="absolute inset-0 bg-gradient-to-r from-marino/70 via-black/55 to-vino/30"></div> {{-- Degradado --}}
    <div class="relative z-10 px-4 text-center text-white">
        <p class="text-xs font-semibold uppercase tracking-[0.25em] text-white/80">
            Laboratorio “Edición Digital”
        </p>
        <h1 class="mt-2 text-4xl font-medium tracking-wide md:text-5xl">
            Podcast
        </h1>
    </div>
</section>

<section class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-4 px-6 pt-8 pb-4">
    <a href="{{ route('home') }}"
       class="group inline-flex items-center justify-center gap-2.5 rounded-lg
              bg-vino px-5 py-2.5 text-sm font-semibold text-white
              shadow-sm transition duration-200
              hover:bg-vino/90 hover:shadow-md
              focus-visible:outline focus-visible:outline-2
              focus-visible:outline-offset-4 focus-visible:outline-marino">
        <x-heroicon-o-arrow-left class="h-4 w-4 transition-transform duration-200 group-hover:-translate-x-1" aria-hidden="true" />
        <span>Regresar al inicio</span>
    </a>

    @if ($podcasts->total() > 0)
        <p class="text-sm text-marino/70">
            Mostrando <span class="font-semibold text-marino">{{ $podcasts->count() }}</span>
            de <span class="font-semibold text-marino">{{ $podcasts->total() }}</span>
            {{ $podcasts->total() === 1 ? 'episodio publicado' : 'episodios publicados' }}
        </p>
    @endif
</section>

{{-- ////////////////////////  LISTADO DE EPISODIOS + REPRODUCTOR MODAL  //////////////////////// --}}
<section class="mx-auto max-w-6xl px-6 py-6"
         x-data="{
            episodio: null,
            copiado: false,
            abrir(data) {
                this.episodio = data;
                this.copiado = false;
                document.body.classList.add('overflow-hidden');
            },
            cerrar() {
                this.episodio = null;
                document.body.classList.remove('overflow-hidden');
            },
            copiarEnlace() {
                if (!this.episodio?.url) return;
                navigator.clipboard.writeText(this.episodio.url);
                this.copiado = true;
                setTimeout(() => this.copiado = false, 2000);
            }
         }"
         @keydown.escape.window="if (episodio) cerrar()">

    <div class="revelar">
        <h2 class="flex items-center gap-3 text-2xl font-bold text-vino sm:text-3xl">
            <span class="h-px w-14 bg-vino"></span> Laboratorio Digital
        </h2>

        <p class="mt-3 max-w-3xl text-base leading-relaxed text-marino/85">
            Laboratorio “Edición Digital”: un espacio de conversación para la creatividad, la cultura y el diseño
            alrededor de los espacios culturales. Haz clic en cualquier episodio para reproducirlo aquí mismo.
        </p>
    </div>

    <div class="mt-10 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($podcasts as $p)
            @php
                $imagen = $p->image_path ? asset('storage/' . $p->image_path) : $p->youtube_thumbnail;

                // Extrae el ID de YouTube si el enlace corresponde a YouTube (sin cambiar tu BD)
                $ytId = null;
                if ($p->video_url && preg_match('/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?|live|shorts)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $p->video_url, $match)) {
                    $ytId = $match[1];
                }

                $payload = [
                    'titulo'      => $p->title,
                    'fecha'       => $p->published_at?->translatedFormat('j \d\e F, Y'),
                    'descripcion' => trim(strip_tags($p->description ?? '')),
                    'url'         => $p->video_url,
                    'ytId'        => $ytId,
                    'imagen'      => $imagen,
                ];
            @endphp

            <article class="revelar group flex h-full flex-col overflow-hidden rounded-xl border border-marino/10 bg-white shadow-sm
                            transition-all duration-300 hover:-translate-y-1 hover:shadow-md">

                {{-- Miniatura interactiva --}}
                <button type="button"
                        @click="abrir(@js($payload))"
                        class="relative block aspect-video w-full overflow-hidden bg-marino/10 text-left
                               focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-[-2px] focus-visible:outline-vino">
                    @if ($imagen)
                        <img src="{{ $imagen }}" alt="{{ $p->title }}"
                             class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                    @else
                        <div class="flex h-full w-full items-center justify-center bg-crema text-marino/30">
                            <x-heroicon-o-microphone class="h-12 w-12" />
                        </div>
                    @endif

                    {{-- Overlay y botón Play --}}
                    <div class="absolute inset-0 bg-black/20 transition-colors duration-300 group-hover:bg-black/40"></div>

                    @if ($p->video_url)
                        <span class="absolute inset-0 flex flex-col items-center justify-center gap-1.5">
                            <span class="flex h-14 w-14 items-center justify-center rounded-full bg-vino/90 text-white shadow-lg transition-transform duration-300 group-hover:scale-110">
                                <x-heroicon-s-play class="ml-0.5 h-7 w-7" />
                            </span>
                            <span class="rounded-full bg-black/60 px-2.5 py-0.5 text-[11px] font-medium text-white opacity-0 backdrop-blur-sm transition-opacity duration-200 group-hover:opacity-100">
                                {{ $ytId ? 'Reproducir aquí' : 'Ver episodio' }}
                            </span>
                        </span>
                    @else
                        <span class="absolute left-3 top-3 rounded-full bg-marino/85 px-3 py-1 text-[10px] font-semibold uppercase tracking-wider text-white">
                            Próximamente
                        </span>
                    @endif
                </button>

                {{-- Contenido de la tarjeta --}}
                <div class="flex flex-1 flex-col p-5">
                    @if ($p->published_at)
                        <p class="flex items-center gap-1.5 text-xs font-medium text-neutral-500">
                            <x-heroicon-o-calendar class="h-3.5 w-3.5 text-vino/70" />
                            {{ $p->published_at->translatedFormat('j \d\e F, Y') }}
                        </p>
                    @endif

                    <h3 class="mt-2 text-lg font-semibold leading-snug text-marino transition-colors group-hover:text-vino">
                        <button type="button" @click="abrir(@js($payload))" class="text-left hover:underline underline-offset-4">
                            {{ $p->title }}
                        </button>
                    </h3>

                    <p class="mt-2 line-clamp-3 text-sm leading-relaxed text-neutral-600">
                        {{ Str::limit(strip_tags($p->description), 140) }}
                    </p>

                    {{-- Pie de tarjeta siempre alineado abajo --}}
                    <div class="mt-auto flex items-center justify-between gap-3 border-t border-marino/10 pt-4 text-xs">
                        <button type="button"
                                @click="abrir(@js($payload))"
                                class="inline-flex items-center gap-1 font-semibold text-vino underline-offset-4 hover:underline">
                            {{ $p->video_url ? ($ytId ? 'Reproducir y leer más' : 'Ver detalles') : 'Leer sinopsis' }}
                            <span aria-hidden="true">→</span>
                        </button>

                        @if ($p->video_url)
                            <a href="{{ $p->video_url }}" target="_blank" rel="noopener"
                               class="text-neutral-500 transition-colors hover:text-marino"
                               title="Abrir en pestaña nueva">
                                Externo ↗
                            </a>
                        @endif
                    </div>
                </div>
            </article>
        @empty
            <div class="rounded-xl border-2 border-dashed border-marino/15 bg-white/60 p-10 text-center sm:col-span-2 lg:col-span-3">
                <x-heroicon-o-microphone class="mx-auto h-10 w-10 text-vino/50" />
                <p class="mt-3 font-medium text-marino">Aún no hay episodios publicados.</p>
                <p class="mt-1 text-sm text-neutral-500">Vuelve pronto para escuchar nuevas conversaciones del Laboratorio.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-10">{{ $podcasts->links() }}</div>

    {{-- ////////////////////////  MODAL REPRODUCTOR INTERACTIVO  //////////////////////// --}}
    <div x-show="episodio" x-cloak
         x-transition.opacity.duration.200ms
         @click.self="cerrar()"
         role="dialog" aria-modal="true" aria-labelledby="modal-podcast-titulo"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/75 p-4 backdrop-blur-sm">

        <div x-show="episodio"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="relative max-h-[90vh] w-full max-w-3xl overflow-y-auto rounded-2xl bg-white shadow-2xl">

            {{-- Botón cerrar --}}
            <button type="button" @click="cerrar()" aria-label="Cerrar reproductor"
                    class="absolute right-3 top-3 z-10 flex h-9 w-9 items-center justify-center rounded-full bg-black/60 text-white transition hover:bg-vino">
                <x-heroicon-o-x-mark class="h-5 w-5" />
            </button>

            {{-- Video incrustado (si es YouTube) o portada --}}
            <template x-if="episodio?.ytId">
                <div class="aspect-video w-full bg-black">
                    <iframe :src="'https://www.youtube-nocookie.com/embed/' + episodio.ytId + '?autoplay=1&rel=0'"
                            title="Reproductor de episodio"
                            class="h-full w-full"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen></iframe>
                </div>
            </template>

            <template x-if="episodio && !episodio.ytId && episodio.imagen">
                <div class="aspect-video w-full overflow-hidden bg-marino/10">
                    <img :src="episodio.imagen" :alt="episodio.titulo" class="h-full w-full object-cover">
                </div>
            </template>

            {{-- Ficha completa del episodio --}}
            <div class="p-6 sm:p-8">
                <p x-show="episodio?.fecha" x-text="episodio?.fecha"
                   class="text-xs font-semibold uppercase tracking-wider text-vino"></p>

                <h3 id="modal-podcast-titulo" x-text="episodio?.titulo"
                    class="mt-1 text-2xl font-semibold leading-snug text-marino"></h3>

                <p x-show="episodio?.descripcion" x-text="episodio?.descripcion"
                   class="mt-4 whitespace-pre-line text-sm leading-relaxed text-neutral-700"></p>

                <div class="mt-6 flex flex-wrap items-center gap-3 border-t border-marino/10 pt-5">
                    <template x-if="episodio?.url">
                        <a :href="episodio.url" target="_blank" rel="noopener"
                           class="inline-flex items-center gap-2 rounded-lg bg-vino px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-vino/90">
                            <x-heroicon-o-arrow-top-right-on-square class="h-4 w-4" />
                            Ver en plataforma original
                        </a>
                    </template>

                    <template x-if="episodio?.url">
                        <button type="button" @click="copiarEnlace()"
                                class="inline-flex items-center gap-2 rounded-lg border border-marino/20 px-4 py-2.5 text-sm font-medium text-marino transition hover:bg-marino/5">
                            <x-heroicon-o-link class="h-4 w-4" />
                            <span x-text="copiado ? '¡Enlace copiado!' : 'Copiar enlace'"></span>
                        </button>
                    </template>

                    <button type="button" @click="cerrar()"
                            class="ml-auto text-sm font-medium text-neutral-500 hover:text-marino">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ////////////////////////  INVITACIÓN AL CANAL  //////////////////////// --}}
<section class="mx-auto max-w-6xl px-6 pb-8 pt-4">
    <div class="flex flex-col items-start justify-between gap-6 rounded-xl border border-marino/10 bg-white p-6 shadow-sm sm:flex-row sm:items-center sm:p-8">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-vino">Sigue la conversación</p>
            <h2 class="mt-1 text-xl font-semibold text-marino">¿Quieres explorar todos nuestros episodios y listas?</h2>
            <p class="mt-1 text-sm text-marino/75">
                Suscríbete a nuestros canales oficiales para recibir notificaciones de nuevas entregas.
            </p>
        </div>

        <div class="flex flex-wrap gap-3">
            @if (config('site.redes.YouTube') && config('site.redes.YouTube') !== '#')
                <a href="{{ config('site.redes.YouTube') }}" target="_blank" rel="noopener"
                   class="inline-flex shrink-0 items-center gap-2 rounded-lg bg-marino px-5 py-3 text-sm font-semibold text-white transition hover:bg-vino">
                    <x-heroicon-o-play-circle class="h-5 w-5" /> Canal de YouTube
                </a>
            @endif

            @if (config('site.redes.Spotify') && config('site.redes.Spotify') !== '#')
                <a href="{{ config('site.redes.Spotify') }}" target="_blank" rel="noopener"
                   class="inline-flex shrink-0 items-center gap-2 rounded-lg border border-marino/20 bg-white px-5 py-3 text-sm font-semibold text-marino transition hover:border-vino hover:text-vino">
                    <x-heroicon-o-musical-note class="h-5 w-5" /> Escuchar en Spotify
                </a>
            @endif

            @if ((!config('site.redes.YouTube') || config('site.redes.YouTube') === '#') && (!config('site.redes.Spotify') || config('site.redes.Spotify') === '#'))
                <a href="{{ config('site.redes.Spotify') ?: config('site.redes.YouTube') }}" target="_blank" rel="noopener"
                   class="inline-flex shrink-0 items-center gap-2 rounded-lg bg-marino px-5 py-3 text-sm font-semibold text-white transition hover:bg-vino">
                    <x-heroicon-o-play-circle class="h-5 w-5" /> Ver canal para más contenido
                </a>
            @endif
        </div>
    </div>
</section>

@endsection
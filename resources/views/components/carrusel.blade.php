@props(['titulo' => null])

<div x-data="{
        mover(dir) {
            this.$refs.pista.scrollBy({ left: dir * this.$refs.pista.clientWidth, behavior: 'smooth' })
        }
    }">

    <div class="mb-3 flex items-center">
        @if ($titulo)
            <h2 class="flex items-center gap-3 text-xl font-bold text-vino">
                <span class="h-px w-14 bg-vino"></span> {{ $titulo }}
            </h2>
        @endif

        <div class="ml-auto flex gap-2">
            <button type="button" @click="mover(-1)" aria-label="Anterior"
                    class="rounded-full border border-vino p-1.5 text-vino hover:bg-vino hover:text-white">
                <x-heroicon-o-arrow-left class="h-4 w-4" />
            </button>
            <button type="button" @click="mover(1)" aria-label="Siguiente"
                    class="rounded-full border border-vino p-1.5 text-vino hover:bg-vino hover:text-white">
                <x-heroicon-o-arrow-right class="h-4 w-4" />
            </button>
        </div>
    </div>

    {{-- La pista: una fila larga que se desliza de lado --}}
    <div x-ref="pista"
         class="-mx-3 flex snap-x snap-mandatory overflow-x-auto scroll-smooth pb-3 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
        {{ $slot }}
    </div>
</div>
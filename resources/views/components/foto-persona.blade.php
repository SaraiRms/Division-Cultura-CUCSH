@props(['path' => null, 'nombre' => '', 'clase' => 'h-24 w-24 rounded-full'])

@if ($path)
    <img src="{{ asset('storage/' . $path) }}" alt="{{ $nombre }}"
         class="{{ $clase }} shrink-0 object-cover">
@else
    <div class="{{ $clase }} flex shrink-0 items-center justify-center bg-neutral-200 text-neutral-400">
        <x-heroicon-o-user class="h-1/2 w-1/2" />
    </div>
@endif
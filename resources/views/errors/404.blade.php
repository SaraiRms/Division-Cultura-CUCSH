@extends('layouts.public')

@section('title', 'Página no encontrada · ' . config('site.nombre'))

@section('content')
<section class="mx-auto flex min-h-[50vh] max-w-3xl flex-col items-center justify-center px-6 py-24 text-center">
    <p class="text-xs font-semibold uppercase tracking-[0.25em] text-vino">Error 404</p>
    <h1 class="mt-4 text-4xl text-marino sm:text-5xl">No encontramos esa página</h1>
    <p class="mt-4 text-neutral-600">Puede que el enlace haya cambiado o que la dirección tenga un error.</p>
    <a href="{{ url('/') }}" class="mt-8 inline-flex items-center gap-2 bg-marino px-6 py-3 font-semibold text-white hover:bg-marino/90">
        Volver al inicio
    </a>
</section>
@endsection
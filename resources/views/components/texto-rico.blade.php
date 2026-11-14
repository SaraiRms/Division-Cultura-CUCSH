@props(['texto' => ''])

@if (filled($texto))
    @if ($texto !== strip_tags($texto))
        <div {{ $attributes->merge(['class' => 'contenido']) }}>{!! $texto !!}</div>
    @else
        <p {{ $attributes->merge(['class' => 'whitespace-pre-line']) }}>{{ $texto }}</p>
    @endif
@endif
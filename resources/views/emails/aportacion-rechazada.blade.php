<x-mail::message>
# Sobre tu participación

Hola, gracias por enviar tu participación a la División de Estudios de la Cultura del CUCSH.

@if ($aportacion->title)
**Título:** {{ $aportacion->title }}
@endif

Después de revisarla, en esta ocasión no fue posible publicarla. Este es el motivo:

<x-mail::panel>
{{ $aportacion->review_note }}
</x-mail::panel>

Puedes atender estas observaciones y enviar una nueva participación cuando quieras.

<x-mail::button :url="route('integracion.index', $aportacion->author_type === 'alumno' ? 'alumnos' : 'profesores')">
Enviar una nueva participación
</x-mail::button>

Saludos,<br>
División de Estudios de la Cultura, CUCSH
</x-mail::message>
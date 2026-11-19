<x-mail::message>
# ¡Tu participación fue publicada!

Hola, te informamos que tu participación **{{ $aportacion->title }}** ya está publicada en el sitio de la División de Estudios de la Cultura del CUCSH.

Gracias por compartirla con la comunidad.

<x-mail::button :url="route('integracion.index', $aportacion->author_type === 'alumno' ? 'alumnos' : 'profesores')">
Ver publicaciones
</x-mail::button>

Saludos,<br>
División de Estudios de la Cultura, CUCSH
</x-mail::message>
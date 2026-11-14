<x-mail::message>
# Nueva aportación

Se recibió una nueva participación desde la página de la División. El documento va adjunto en este correo.

**Tipo:** {{ $aportacion->author_type === 'alumno' ? 'Estudiante' : 'Académico' }}

**Título:** {{ $aportacion->title }}

**Correo de contacto:** {{ $aportacion->contact_email }}

**Comentarios:** {{ $aportacion->comentarios ?: 'Sin comentarios' }}

<x-mail::button :url="url('/admin')">
Revisar en el panel
</x-mail::button>
</x-mail::message>

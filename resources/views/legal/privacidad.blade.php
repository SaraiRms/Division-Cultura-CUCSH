@extends('layouts.public')

@section('title', 'Aviso de privacidad · ' . config('site.nombre'))

@section('content')
<article class="mx-auto max-w-3xl px-6 py-12 text-marino">

    <h1 class="flex items-center gap-3 text-3xl text-vino">
        <span class="h-px w-14 bg-vino"></span> Aviso de privacidad
    </h1>
    <p class="mt-2 text-sm text-neutral-500">Última actualización: noviembre de 2026</p>

    <div class="mt-8 space-y-8 leading-relaxed">

        <section>
            <h2 class="text-xl text-vino">1. Responsable del tratamiento de tus datos</h2>
            <p class="mt-2">
                La Universidad de Guadalajara, a través de la División de Estudios de la Cultura del Centro
                Universitario de Ciencias Sociales y Humanidades (CUCSH), es la responsable del tratamiento de
                los datos personales que se recaban mediante este sitio web.
            </p>
        </section>

        <section>
            <h2 class="text-xl text-vino">2. Qué datos recabamos</h2>
            <p class="mt-2">Solo se recaban datos cuando envías una participación en la sección de Participación Académica:</p>
            <ul class="mt-2 list-disc space-y-1 pl-6">
                <li>Tu correo electrónico de contacto.</li>
                <li>El título de tu documento y tus comentarios (si los escribes).</li>
                <li>El documento en PDF que adjuntas. Puede incluir datos personales que tú mismo escribas en él, como tu nombre.</li>
            </ul>
            <p class="mt-3">
                <strong>No solicitamos datos personales sensibles</strong> (salud, creencias, origen étnico, entre
                otros). Te pedimos no incluirlos en tu documento ni en tus comentarios.
            </p>
            <p class="mt-3">
                Para evitar envíos automáticos, el sitio puede registrar temporalmente la dirección IP desde la que
                se hace un envío, solo para limitar la cantidad de envíos por hora.
            </p>
        </section>

        <section>
            <h2 class="text-xl text-vino">3. Para qué usamos tus datos</h2>
            <ul class="mt-2 list-disc space-y-1 pl-6">
                <li>Recibir y revisar tu participación.</li>
                <li>Contactarte para informarte el resultado de la revisión, pedirte información adicional o comunicarte observaciones.</li>
                <li>Publicar tu documento en este sitio, únicamente si es aprobado.</li>
            </ul>
            <p class="mt-3">No utilizamos tus datos para fines de mercadotecnia, publicidad ni perfilado, y no tenemos finalidades secundarias.</p>
            <p class="mt-3">Tu correo electrónico <strong>nunca se publica</strong>. Si tu documento es aprobado, se publica tal como lo enviaste, por lo que cualquier dato personal que aparezca en él será visible.</p>
        </section>

        <section>
            <h2 class="text-xl text-vino">4. Transferencias</h2>
            <p class="mt-2">
                No compartimos tus datos personales con terceros, salvo en los casos previstos por la ley o cuando
                lo requiera una autoridad competente.
            </p>
        </section>

        <section>
            <h2 class="text-xl text-vino">5. Contenido de terceros en este sitio</h2>
            <p class="mt-2">
                Algunas páginas muestran videos o imágenes de YouTube, y el sitio carga algunos recursos técnicos
                desde servicios externos. Al usarlos, esos servicios pueden recibir datos técnicos de tu conexión
                (como tu dirección IP) y se rigen por sus propias políticas de privacidad. Los videos de YouTube solo
                se cargan cuando decides reproducirlos.
            </p>
            <p class="mt-2">El sitio usa únicamente <em>cookies</em> técnicas necesarias para su funcionamiento (por ejemplo, para el envío seguro de formularios y el acceso del personal autorizado). No usamos <em>cookies</em> de publicidad ni de análisis.</p>
        </section>

        <section>
            <h2 class="text-xl text-vino">6. Tus derechos (ARCO)</h2>
            <p class="mt-2">
                Tienes derecho a Acceder a tus datos personales, Rectificarlos, Cancelarlos u Oponerte a su uso
                (derechos ARCO), así como a solicitar la portabilidad de tus datos. Para ejercerlos, puedes
                presentar tu solicitud ante la Unidad de Transparencia de la Universidad de Guadalajara
                @if (config('site.transparencia_url'))
                    (<a href="{{ config('site.transparencia_url') }}" target="_blank" rel="noopener" class="text-vino underline">{{ config('site.transparencia_url') }}</a>)
                @endif
                o escribirnos desde la página de <a href="{{ route('legal.contacto') }}" class="text-vino underline">Contacto</a>.
            </p>
        </section>

        <section>
            <h2 class="text-xl text-vino">7. Cuánto tiempo conservamos tus datos</h2>
            <p class="mt-2">
                Conservamos tus datos durante el tiempo necesario para cumplir las finalidades anteriores y los plazos
                que establezca la normativa de archivos aplicable a la Universidad.
            </p>
        </section>

        <section>
            <h2 class="text-xl text-vino">8. Marco legal</h2>
            <p class="mt-2">
                Este aviso se emite con fundamento en la Ley General de Protección de Datos Personales en Posesión de
                Sujetos Obligados y en la Ley de Protección de Datos Personales en Posesión de Sujetos Obligados del
                Estado de Jalisco y sus Municipios.
            </p>
        </section>

        <section>
            <h2 class="text-xl text-vino">9. Cambios a este aviso</h2>
            <p class="mt-2">
                Si este aviso cambia, publicaremos la versión actualizada en esta misma página, con su fecha de
                actualización.
            </p>
        </section>

    </div>
</article>
@endsection

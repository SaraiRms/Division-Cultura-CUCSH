@extends('layouts.public')

@section('title', 'Términos y condiciones · ' . config('site.nombre'))

@section('content')
<article class="mx-auto max-w-3xl px-6 py-12 text-marino">

    <h1 class="flex items-center gap-3 text-3xl text-vino">
        <span class="h-px w-14 bg-vino"></span> Términos y condiciones
    </h1>
    <p class="mt-2 text-sm text-neutral-500">Última actualización: noviembre de 2026</p>

    <div class="mt-8 space-y-8 leading-relaxed">

        <section>
            <h2 class="text-xl text-vino">1. Sobre este sitio</h2>
            <p class="mt-2">
                Este sitio web es un espacio de difusión de la División de Estudios de la Cultura del Centro
                Universitario de Ciencias Sociales y Humanidades (CUCSH) de la Universidad de Guadalajara. Al
                navegarlo o usar sus formularios, aceptas estos términos.
            </p>
        </section>

        <section>
            <h2 class="text-xl text-vino">2. Uso del contenido</h2>
            <p class="mt-2">
                Los textos, imágenes, logotipos y demás materiales publicados por la División son propiedad de la
                Universidad de Guadalajara o se usan con autorización de sus titulares. Puedes consultarlos y
                compartirlos con fines académicos y culturales, citando la fuente. Su uso comercial o su modificación
                requiere autorización por escrito.
            </p>
        </section>

        <section>
            <h2 class="text-xl text-vino">3. Participaciones enviadas por la comunidad</h2>
            <p class="mt-2">Si envías un documento en la sección de Participación Académica:</p>
            <ul class="mt-2 list-disc space-y-1 pl-6">
                <li>Declaras que eres autor o autora del trabajo, o que cuentas con autorización para compartirlo, y que no infringe derechos de terceros.</li>
                <li>Conservas la titularidad de tus derechos de autor. Si tu trabajo es aprobado, nos autorizas a publicarlo en este sitio de forma no exclusiva, sin fines de lucro y con fines de difusión académica.</li>
                <li>El documento debe estar en formato PDF y no contener contenido inapropiado, ofensivo, ilícito ni archivos o elementos que comprometan la seguridad de quienes lo consulten.</li>
                <li>El equipo de la División revisa cada envío y decide si se publica. <strong>Enviar un documento no garantiza su publicación.</strong></li>
                <li>Podemos retirar una participación publicada si recibimos una solicitud fundada o si detectamos un incumplimiento de estos términos.</li>
            </ul>
        </section>

        <section>
            <h2 class="text-xl text-vino">4. Enlaces y contenido externo</h2>
            <p class="mt-2">
                Este sitio enlaza a páginas y servicios de terceros (por ejemplo YouTube, Spotify y redes sociales).
                No controlamos su contenido ni sus políticas y no somos responsables por ellos.
            </p>
        </section>

        <section>
            <h2 class="text-xl text-vino">5. Disponibilidad</h2>
            <p class="mt-2">
                Procuramos que el sitio esté disponible y actualizado, pero no garantizamos que funcione sin
                interrupciones ni que esté libre de errores. Podemos modificar, suspender o retirar contenidos sin
                previo aviso.
            </p>
        </section>

        <section>
            <h2 class="text-xl text-vino">6. Privacidad</h2>
            <p class="mt-2">
                El tratamiento de tus datos personales se explica en el
                <a href="{{ route('legal.privacidad') }}" class="text-vino underline">Aviso de privacidad</a>.
            </p>
        </section>

        <section>
            <h2 class="text-xl text-vino">7. Cambios y legislación aplicable</h2>
            <p class="mt-2">
                Podemos actualizar estos términos; la versión vigente es la publicada en esta página. Se rigen por las
                leyes aplicables en el Estado de Jalisco y en los Estados Unidos Mexicanos, y por la normativa de la
                Universidad de Guadalajara.
            </p>
        </section>

        <section>
            <h2 class="text-xl text-vino">8. Contacto</h2>
            <p class="mt-2">
                Si tienes dudas sobre estos términos, escríbenos desde la página de
                <a href="{{ route('legal.contacto') }}" class="text-vino underline">Contacto</a>.
            </p>
        </section>

    </div>
</article>
@endsection

<?php

namespace App\Http\Controllers;

use App\Mail\NuevaAportacion;
use App\Models\Contribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ContributionController extends Controller
{
    // Lo que aparece en la dirección  →  lo que se guarda en la base de datos
    private const TIPOS = [
        'alumnos'    => 'alumno',
        'profesores' => 'profesor',
    ];

    // Lista de lo ya publicado
    public function index(string $tipo)
    {
        $aportaciones = Contribution::published()
            ->ofType(self::TIPOS[$tipo])
            ->latest()
            ->paginate(12);

        return view('integracion.index', compact('tipo', 'aportaciones'));
    }

    // El formulario
    public function create(string $tipo)
    {
        return view('integracion.create', compact('tipo'));
    }

    // Recibir el formulario
    public function store(Request $request, string $tipo)
    {
        // Trampa para robots: este campo está escondido y una persona nunca lo llena.
        // Si viene lleno, fingimos que todo salió bien y no guardamos nada.
        if ($request->filled('sitio_web')) {
            return redirect()->route('integracion.index', $tipo)->with('enviado', true);
        }

        $datos = $request->validate([
            'contact_email' => ['required', 'email:rfc,dns', 'max:190'],
            'comentarios'   => ['nullable', 'string', 'max:1000'],
            'titulo'        => ['required', 'string', 'max:200'],
            'pdf' => [
                'bail', 'required', 'file', 'mimes:pdf', 'mimetypes:application/pdf', 'max:5120', // 5 MB
                function ($atributo, $valor, $fallar) {
                    // Un PDF de verdad empieza con los caracteres "%PDF-"
                    if (file_get_contents($valor->getRealPath(), false, null, 0, 5) !== '%PDF-') {
                        $fallar('El archivo no parece ser un PDF válido.');
                    }
                },
            ],
            'acepto' => ['accepted'],
        ], [
            'contact_email.required' => 'Escribe tu correo de contacto.',
            'contact_email.email'    => 'Escribe un correo electrónico válido.',
            'comentarios.max'        => 'Los comentarios no pueden pasar de 1000 caracteres.',
            'pdf.required'           => 'Adjunta tu documento en PDF.',
            'pdf.mimes'              => 'El documento debe ser un archivo PDF.',
            'pdf.mimetypes'          => 'El documento debe ser un archivo PDF.',
            'pdf.max'                => 'El PDF no puede pesar más de 5 MB.',
            'pdf.uploaded'           => 'No se pudo subir el archivo. Revisa que no pese más de 5 MB.',
            'acepto.accepted'        => 'Debes aceptar el aviso de privacidad para enviar tu participación.',
            'titulo.required'        => 'Escribe el título de tu documento.',
            'titulo.max'             => 'El título no puede pasar de 200 caracteres.',
        ]);

        // Carpeta PRIVADA (storage/app/private), con un nombre al azar que genera Laravel
        $ruta = $request->file('pdf')->store('aportaciones', 'local');

        $aportacion = Contribution::create([
            'author_type'   => self::TIPOS[$tipo],
            'title'         => $datos['titulo'],
            'contact_email' => $datos['contact_email'],
            'comentarios'   => $datos['comentarios'] ?? null,
            'pdf_path'      => $ruta,
            // 'status' queda en 'received' por defecto
        ]);

        // Avisar al buzón del equipo
        $correoEquipo = config('site.correo_aportaciones');

        if ($correoEquipo) {
            try {
                Mail::to($correoEquipo)->send(new NuevaAportacion($aportacion));
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return redirect()->route('integracion.index', $tipo)->with('enviado', true);
    }

    // Abrir el PDF de una participación YA publicada
    public function document(Contribution $contribution)
    {
        abort_unless($contribution->status === 'published', 404);
        abort_unless(Storage::disk('local')->exists($contribution->pdf_path), 404);

        return Storage::disk('local')->response(
            $contribution->pdf_path,
            'participacion-' . $contribution->id . '.pdf',
            ['Content-Type' => 'application/pdf', 'X-Content-Type-Options' => 'nosniff'],
            'inline'
        );
    }

    // Para el equipo: abre el PDF de cualquier aportación, aunque no esté publicada
    public function adminDocument(Contribution $contribution)
    {
        abort_unless(auth()->user()?->can('revisar_contenido'), 403);
        abort_unless(Storage::disk('local')->exists($contribution->pdf_path), 404);

        return Storage::disk('local')->response(
            $contribution->pdf_path,
            'participacion-' . $contribution->id . '.pdf',
            ['Content-Type' => 'application/pdf', 'X-Content-Type-Options' => 'nosniff'],
            'inline'
        );
    }

}
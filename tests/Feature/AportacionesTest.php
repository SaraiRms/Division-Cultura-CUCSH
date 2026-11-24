<?php

namespace Tests\Feature;

use App\Mail\NuevaAportacion;
use App\Models\Contribution;
use App\Models\User;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Mail\AportacionRechazada;
use App\Mail\AportacionPublicada;

class AportacionesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        Storage::fake('local');
        Mail::fake();
        config(['site.correo_aportaciones' => 'equipo@example.test']);
    }

    private function datos(array $cambios = []): array
    {
        return array_merge([
            // Se usa un dominio real porque la regla "email:rfc,dns" consulta el DNS (necesita internet).
            'contact_email' => 'prueba@gmail.com',
            'titulo'        => 'Mi documento de prueba',
            'comentarios'   => 'Gracias',
            'pdf'           => UploadedFile::fake()->createWithContent('doc.pdf', "%PDF-1.4\n%prueba\n"),
            'acepto'        => '1',
        ], $cambios);
    }

    public function test_una_participacion_valida_se_guarda_y_avisa_al_equipo(): void
    {
        $this->post('/integracion/alumnos/participar', $this->datos())
            ->assertRedirect('/integracion/alumnos')
            ->assertSessionHas('enviado');

        $this->assertDatabaseHas('contributions', [
            'author_type' => 'alumno',
            'title'       => 'Mi documento de prueba',
            'status'      => 'in_review',
        ]);

        Mail::assertSent(NuevaAportacion::class);
    }

    public function test_rechaza_archivos_que_no_son_pdf(): void
    {
        $this->post('/integracion/alumnos/participar', $this->datos([
            'pdf' => UploadedFile::fake()->createWithContent('malo.pdf', 'esto no es un pdf'),
        ]))->assertSessionHasErrors('pdf');

        $this->post('/integracion/alumnos/participar', $this->datos([
            'pdf' => UploadedFile::fake()->create('foto.jpg', 10, 'image/jpeg'),
        ]))->assertSessionHasErrors('pdf');

        $this->assertDatabaseCount('contributions', 0);
    }

    public function test_rechaza_pdf_de_mas_de_5_mb(): void
    {
        $this->post('/integracion/alumnos/participar', $this->datos([
            'pdf' => UploadedFile::fake()->create('grande.pdf', 6000, 'application/pdf'),
        ]))->assertSessionHasErrors('pdf');
    }

    public function test_exige_titulo_y_aviso_de_privacidad(): void
    {
        $this->post('/integracion/alumnos/participar', $this->datos(['titulo' => '', 'acepto' => null]))
            ->assertSessionHasErrors(['titulo', 'acepto']);
    }

    public function test_la_trampa_para_robots_no_guarda_nada(): void
    {
        $this->post('/integracion/alumnos/participar', $this->datos(['sitio_web' => 'spam.com']));

        $this->assertDatabaseCount('contributions', 0);
        Mail::assertNothingSent();
    }

    public function test_el_pdf_privado_solo_lo_abre_quien_revisa(): void
    {
        $this->seed(RolesSeeder::class);
        Storage::disk('local')->put('aportaciones/x.pdf', "%PDF-1.4\n");

        $aportacion = Contribution::create([
            'author_type'   => 'alumno',
            'contact_email' => 'a@example.test',
            'pdf_path'      => 'aportaciones/x.pdf',
        ]);

        // Visitante sin sesión
        $this->get(route('aportaciones.pdf', $aportacion))->assertForbidden();

        // Auxiliar (no revisa)
        $auxiliar = User::factory()->create(['is_active' => true]);
        $auxiliar->assignRole('auxiliar_academico');
        $this->actingAs($auxiliar)->get(route('aportaciones.pdf', $aportacion))->assertForbidden();

        // Editor (sí revisa)
        $editor = User::factory()->create(['is_active' => true]);
        $editor->assignRole('editor');
        $this->actingAs($editor)->get(route('aportaciones.pdf', $aportacion))->assertOk();
    }

    public function test_el_pdf_publico_solo_si_esta_publicada(): void
    {
        Storage::disk('local')->put('aportaciones/y.pdf', "%PDF-1.4\n");

        $aportacion = Contribution::create([
            'author_type'   => 'profesor',
            'contact_email' => 'b@example.test',
            'pdf_path'      => 'aportaciones/y.pdf',
        ]);

        $this->get(route('integracion.documento', $aportacion))->assertNotFound();

        $aportacion->update(['status' => 'published']);

        $this->get(route('integracion.documento', $aportacion))->assertOk();
    }

    public function test_el_correo_de_rechazo_lleva_el_motivo(): void
    {
        $aportacion = Contribution::create([
            'author_type'   => 'alumno',
            'contact_email' => 'persona@example.test',
            'pdf_path'      => 'aportaciones/x.pdf',
            'title'         => 'Mi texto',
            'status'        => 'rejected',
            'review_note'   => 'Falta incluir la bibliografía.',
        ]);

        $correo = new AportacionRechazada($aportacion);

        $correo->assertSeeInHtml('Falta incluir la bibliografía.');
        $correo->assertSeeInHtml('Mi texto');
    }

    public function test_el_correo_de_publicacion_lleva_el_titulo(): void
    {
        $aportacion = Contribution::create([
            'author_type'   => 'profesor',
            'contact_email' => 'persona@example.test',
            'pdf_path'      => 'aportaciones/x.pdf',
            'title'         => 'Mi ensayo',
            'status'        => 'published',
        ]);

        (new AportacionPublicada($aportacion))->assertSeeInHtml('Mi ensayo');
    }
    
}
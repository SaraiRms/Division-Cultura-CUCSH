<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\News;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SitioPublicoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite(); // en pruebas no hace falta compilar el CSS
    }

    public static function paginas(): array
    {
        return [
            ['/'], ['/noticias'], ['/eventos'], ['/departamentos'], ['/directorio'],
            ['/podcast'], ['/conversatorios'], ['/egresados'],
            ['/integracion/alumnos'], ['/integracion/profesores'],
            ['/integracion/alumnos/participar'], ['/integracion/profesores/participar'],
            ['/aviso-de-privacidad'], 
            ['/terminos-y-condiciones'], 
            ['/contacto']
        ];
    }

    #[DataProvider('paginas')]
    public function test_las_paginas_publicas_cargan(string $url): void
    {
        $this->get($url)->assertOk();
    }

    public function test_una_noticia_sin_publicar_no_se_ve(): void
    {
        $noticia = News::create(['title' => 'Borrador secreto', 'status' => 'pending']);

        $this->get(route('noticias.show', $noticia))->assertNotFound();
        $this->get('/noticias')->assertDontSee('Borrador secreto');
    }

    public function test_un_evento_en_borrador_no_se_ve(): void
    {
        $evento = Event::create([
            'title'     => 'Evento en borrador',
            'starts_at' => now()->addDays(3),
            'status'    => 'draft',
        ]);

        $this->get(route('eventos.show', $evento))->assertNotFound();
    }

    public function test_el_html_peligroso_de_una_noticia_se_limpia(): void
    {
        $noticia = News::create([
            'title'        => 'Noticia con HTML raro',
            'status'       => 'published',
            'published_at' => now()->subDay(),
            'body'         => '<p>Texto seguro</p><script>alert("xss")</script><img src="x" onerror="alert(2)">',
        ]);

        $this->get(route('noticias.show', $noticia))
            ->assertOk()
            ->assertSee('Texto seguro')
            ->assertDontSee('<script>alert("xss")</script>', false)
            ->assertDontSee('onerror', false);
    }

    public function test_la_pagina_404_tiene_el_diseno_del_sitio(): void
    {
        $this->get('/esta-pagina-no-existe')
            ->assertNotFound()
            ->assertSee('No encontramos esa página');
    }
}
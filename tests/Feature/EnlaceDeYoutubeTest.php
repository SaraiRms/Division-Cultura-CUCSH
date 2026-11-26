<?php

namespace Tests\Feature;

use App\Rules\EnlaceDeYoutube;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class EnlaceDeYoutubeTest extends TestCase
{
    public static function validos(): array
    {
        return [
            ['https://www.youtube.com/watch?v=dQw4w9WgXcQ'],
            ['https://youtu.be/dQw4w9WgXcQ'],
            ['https://www.youtube.com/live/XgeESG_PwIc?si=9k_Sbvqhrko1zgO0'],
            ['https://www.youtube.com/embed/dQw4w9WgXcQ'],
            ['https://www.youtube.com/watch?feature=shared&v=dQw4w9WgXcQ'],
        ];
    }

    public static function invalidos(): array
    {
        return [
            ['hola'],
            ['https://google.com'],
            ['https://vimeo.com/123456789'],
            ['https://www.youtube.com/@DIVISIONDEESTUDIOSDELACULTURA'],
            ['https://www.youtube.com/watch'],
            ['https://evil.com/?x=youtu.be/dQw4w9WgXcQ'],
            ['https://youtube.com.evil.com/watch?v=dQw4w9WgXcQ'],
        ];
    }

    #[DataProvider('validos')]
    public function test_acepta_enlaces_de_youtube(string $url): void
    {
        $this->assertFalse(Validator::make(['u' => $url], ['u' => [new EnlaceDeYoutube]])->fails(), $url);
    }

    #[DataProvider('invalidos')]
    public function test_rechaza_lo_que_no_es_un_video_de_youtube(string $url): void
    {
        $this->assertTrue(Validator::make(['u' => $url], ['u' => [new EnlaceDeYoutube]])->fails(), $url);
    }
}
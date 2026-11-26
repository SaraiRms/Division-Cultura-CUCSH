<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

// Acepta solo enlaces de video de YouTube (watch, youtu.be, embed, live o shorts).
// La misma lógica saca el ID del video, así que "válido" significa "el sitio sí puede mostrarlo".
class EnlaceDeYoutube implements ValidationRule
{
    // Devuelve el ID de 11 caracteres del video, o null si no es un enlace de YouTube.
    public static function id(?string $url): ?string
    {
        $partes = parse_url(trim((string) $url));

        if (! $partes || ! in_array(strtolower($partes['scheme'] ?? ''), ['http', 'https'], true)) {
            return null;
        }

        $host = strtolower(preg_replace('/^(www\.|m\.)/i', '', $partes['host'] ?? ''));
        $ruta = $partes['path'] ?? '';
        $id = null;

        if ($host === 'youtu.be') {
            $id = explode('/', ltrim($ruta, '/'))[0];
        } elseif (in_array($host, ['youtube.com', 'youtube-nocookie.com'], true)) {
            if ($ruta === '/watch') {
                parse_str($partes['query'] ?? '', $consulta);
                $id = is_string($consulta['v'] ?? null) ? $consulta['v'] : null;
            } elseif (preg_match('~^/(?:embed|live|shorts)/([^/?#]+)~', $ruta, $m)) {
                $id = $m[1];
            }
        }

        return is_string($id) && preg_match('/^[\w-]{11}$/', $id) ? $id : null;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (self::id(is_string($value) ? $value : null) === null) {
            $fail('Escribe un enlace de YouTube, por ejemplo https://www.youtube.com/watch?v=XXXXXXXXXXX o https://youtu.be/XXXXXXXXXXX.');
        }
    }
}
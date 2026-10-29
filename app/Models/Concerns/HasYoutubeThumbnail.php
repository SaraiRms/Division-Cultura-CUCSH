<?php

namespace App\Models\Concerns;

// Saca la miniatura de un video de YouTube a partir de su URL, sin guardar nada extra en la BD.
trait HasYoutubeThumbnail
{
    // Campo del modelo que contiene la URL del video (lo define cada modelo).
    abstract protected function youtubeUrlField(): string;

    public function getYoutubeIdAttribute(): ?string
    {
        $url = (string) $this->{$this->youtubeUrlField()};

        if (preg_match('~(?:youtu\.be/|youtube\.com/(?:watch\?v=|embed/|live/|shorts/))([\w-]{11})~', $url, $m)) {
            return $m[1];
        }

        return null;
    }

    public function getYoutubeThumbnailAttribute(): ?string
    {
        return $this->youtube_id ? "https://img.youtube.com/vi/{$this->youtube_id}/hqdefault.jpg" : null;
    }
}

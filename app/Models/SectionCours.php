<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SectionCours extends Model
{
    // On force le nom de la table exacte
    protected $table = 'sections_cours';

    protected $fillable = ['type', 'titre', 'contenu', 'ordre', 'id_cours'];

    public function cours(): BelongsTo
    {
        return $this->belongsTo(Cours::class, 'id_cours');
    }
    public function getYoutubeEmbedUrlAttribute()
    {
        if (!$this->contenu) {
            return '';
        }

        // Capture l'ID YouTube peu importe le format d'URL
        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $this->contenu, $match);

        if (isset($match[1])) {
            return "https://www.youtube.com/embed/" . $match[1];
        }

        return $this->contenu;
    }
}
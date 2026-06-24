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
}
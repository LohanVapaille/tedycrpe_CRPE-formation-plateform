<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cours extends Model
{
    protected $fillable = ['name', 'ordre', 'id_matiere'];

    // Liaison inverse vers Matiere
    public function matiere(): BelongsTo
    {
        return $this->belongsTo(Matiere::class, 'id_matiere');
    }

    // Liaison vers les Sections
    public function sections(): HasMany
    {
        return $this->hasMany(SectionCours::class, 'id_cours');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RenduDevoir extends Model
{
    protected $fillable = [
        'section_cours_id',
        'user_id',
        'nom_origine',
        'chemin_fichier',
    ];

    // Relation vers la section
    public function section()
    {
        return $this->belongsTo(SectionCours::class, 'section_cours_id');
    }

    // Relation vers l'élève
    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
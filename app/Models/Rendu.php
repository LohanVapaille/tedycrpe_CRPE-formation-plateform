<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rendu extends Model
{
    use HasFactory;

    protected $table = 'rendus'; // Optionnel si Laravel suit la convention pluriel

    protected $fillable = [
        'id_user',
        'id_cours',
        'nom_origine',
        'chemin_fichier',
    ];

    // Relation inverse : un rendu appartient à un utilisateur
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}

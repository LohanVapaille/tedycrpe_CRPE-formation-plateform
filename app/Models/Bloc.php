<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bloc extends Model
{
    protected $fillable = ['name', 'ordre'];

    public function matieres(): HasMany
    {
        return $this->hasMany(Matiere::class, 'bloc_id');
    }
}

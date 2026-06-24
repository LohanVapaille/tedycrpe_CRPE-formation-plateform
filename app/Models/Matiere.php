<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Matiere extends Model
{
    protected $fillable = ['name', 'module_associe', 'ordre'];

    public function cours(): HasMany
    {
        return $this->hasMany(Cours::class, 'id_matiere');
    }
}
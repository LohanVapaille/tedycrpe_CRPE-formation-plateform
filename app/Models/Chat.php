<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chat extends Model
{
    // On spécifie le nom exact de la table au singulier
    protected $table = 'chat';

    protected $fillable = ['id_user'];

    /**
     * Le chat appartient à un utilisateur (l'étudiant).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Un chat contient plusieurs messages.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'id_chat');
    }
}
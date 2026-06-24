<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = ['contenu', 'id_writer', 'id_chat'];

    /**
     * Le message a été écrit par un utilisateur spécifique.
     */
    public function writer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_writer');
    }

    /**
     * Le message appartient à un chat spécifique.
     */
    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class, 'id_chat');
    }
}
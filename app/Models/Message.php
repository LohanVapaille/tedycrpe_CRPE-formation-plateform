<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = ['contenu', 'id_writer', 'id_chat', 'read_at'];

    protected $casts = [
        'read_at' => 'datetime',
    ];

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

    /**
     * Messages non lus destinés à l'utilisateur donné :
     * - tuteur : messages écrits par l'élève propriétaire du chat
     * - élève : messages de son chat écrits par quelqu'un d'autre
     */
    public function scopeUnreadFor(Builder $query, User $user): Builder
    {
        $query->whereNull('messages.read_at');

        if ($user->is_tutor) {
            return $query->whereIn('messages.id_chat', function ($sub) {
                $sub->select('chat.id')->from('chat')->whereColumn('chat.id_user', 'messages.id_writer');
            });
        }

        return $query->where('messages.id_writer', '!=', $user->id)
            ->whereIn('messages.id_chat', Chat::where('id_user', $user->id)->select('id'));
    }
}

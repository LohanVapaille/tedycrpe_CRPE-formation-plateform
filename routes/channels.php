<?php

use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{id_chat}', function (User $user, int $id_chat) {
    $chat = Chat::find($id_chat);

    \Log::info('Auth canal', [
        'user_id' => $user->id,
        'chat_id' => $id_chat,
        'chat' => $chat,
        'id_user' => $chat?->id_user,
        'is_tutor' => $user->is_tutor ?? 'colonne absente',
    ]);

    if (!$chat)
        return false;

    return $user->id == $chat->id_user || ($user->is_tutor ?? 0) == 1;
});
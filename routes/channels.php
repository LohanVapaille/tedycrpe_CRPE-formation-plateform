<?php

use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{id_chat}', function (User $user, int $id_chat) {
    if ($user->is_tutor) {
        return true;
    }

    return Chat::where('id', $id_chat)->where('id_user', $user->id)->exists();
});

// Canal du tuteur : reçoit les messages de tous les élèves
Broadcast::channel('tutor', function (User $user) {
    return (bool) $user->is_tutor;
});

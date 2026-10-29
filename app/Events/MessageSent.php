<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * ShouldBroadcastNow : envoyé immédiatement, sans passer par la file d'attente
 * (en prod QUEUE_CONNECTION=database sans worker, l'événement ne partirait jamais).
 */
class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message->loadMissing(['writer', 'chat']);
    }

    public function broadcastOn(): array
    {
        $channels = [new PrivateChannel('chat.' . $this->message->id_chat)];

        // Message d'un élève : le tuteur est prévenu sur un canal unique, quelle que soit la conversation
        if ($this->message->id_writer == $this->message->chat?->id_user) {
            $channels[] = new PrivateChannel('tutor');
        }

        return $channels;
    }

    public function broadcastAs(): string
    {
        return 'MessageSent';
    }

    /**
     * Données minimales envoyées au navigateur (pas d'email ni d'autres infos du compte).
     */
    public function broadcastWith(): array
    {
        return [
            'message' => [
                'id' => $this->message->id,
                'id_chat' => $this->message->id_chat,
                'id_writer' => $this->message->id_writer,
                'contenu' => $this->message->contenu,
                'created_at' => $this->message->created_at,
                'writer' => [
                    'firstname' => $this->message->writer?->firstname,
                    'name' => $this->message->writer?->name,
                ],
            ],
        ];
    }
}

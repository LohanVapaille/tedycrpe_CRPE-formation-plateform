@auth
    <script>
        window.chatConfig = {
            currentUserId: {{ Auth::id() }},
            isTuteur: {{ Auth::user()->is_tutor ? 'true' : 'false' }},
            initialChatId: {{ !Auth::user()->is_tutor && isset($chat) ? $chat->id : 'null' }},
            initialUnread: {{ (int) ($chatUnread ?? 0) }},
            baseUrl: @json(url('')),
            csrfToken: @json(csrf_token()),
            pusherKey: @json(config('broadcasting.connections.pusher.key')),
            pusherCluster: @json(config('broadcasting.connections.pusher.options.cluster')),
        };
    </script>
@endauth

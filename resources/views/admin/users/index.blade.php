{{-- resources/views/admin/users/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'Utilisateurs')

@section('content')
    <div class="admin-header">
        <h1>Utilisateurs</h1>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class='bx bx-plus'></i> Nouvel utilisateur
        </a>
    </div>

    @if(session('success'))
        <p style="color:green; margin-bottom:1rem;">{{ session('success') }}</p>
    @endif
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Accès</th>
                    <th>Rôle</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->firstname }} {{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ \App\Models\User::ACCES[$user->access] ?? $user->access }}</td>
                        <td>{{ $user->is_tutor ? 'Tuteur' : 'Élève' }}</td>
                        <td style="display:flex; gap:0.5rem;">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-secondary">
                                <i class='bx bx-edit'></i>
                            </a>
                            @if(!$user->is_tutor && $user->chats->isNotEmpty())
                                <button class="btn btn-primary open-chat-btn" data-chat-id="{{ $user->chats->first()->id }}"
                                    data-student-name="{{ $user->firstname }} {{ $user->name }}">
                                    <i class='bx bx-message-square-dots'></i>
                                </button>
                            @endif
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                onsubmit="return confirm('Supprimer ?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger"><i class='bx bx-trash'></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @include('components.chat')

    @push('scripts')
        <script>
            // Même JS que backoffice pour le chat
            document.addEventListener('DOMContentLoaded', () => {
                const baseUrl = "{{ url('') }}";
                const currentUserId = {{ Auth::id() }};
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const chatPopup = document.getElementById('chat-popup');
                const chatMessagesContainer = document.getElementById('chat-messages-container');
                const chatInput = document.getElementById('chat-input');
                const sendBtn = document.getElementById('send-btn');
                const chatStudentName = document.getElementById('chat-student-name');

                let activeChatId = null;
                let activeEchoChannel = null;

                function appendMessage(contenu, direction, writerName = '', createdAt = null) {
                    const div = document.createElement('div');
                    div.classList.add('message', direction);
                    const timeStr = createdAt
                        ? new Date(createdAt).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })
                        : new Date().toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
                    div.innerHTML = `${writerName ? `<span class="message-author">${writerName}</span>` : ''}<p>${contenu}</p><span class="message-time">${timeStr}</span>`;
                    chatMessagesContainer.appendChild(div);
                    chatMessagesContainer.scrollTop = chatMessagesContainer.scrollHeight;
                }

                function loadHistory(chatId) {
                    chatMessagesContainer.innerHTML = '<p>Chargement...</p>';
                    fetch(`${baseUrl}/chat/${chatId}/messages`, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
                    }).then(r => r.json()).then(messages => {
                        chatMessagesContainer.innerHTML = '';
                        messages.forEach(msg => {
                            const direction = msg.id_writer === currentUserId ? 'outgoing' : 'incoming';
                            const writerName = direction === 'incoming' ? `${msg.writer.firstname} ${msg.writer.name}` : '';
                            appendMessage(msg.contenu, direction, writerName, msg.created_at);
                        });
                    });
                }

                document.querySelectorAll('.open-chat-btn').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const chatId = btn.dataset.chatId;
                        activeChatId = chatId;
                        chatStudentName.textContent = `Discussion avec ${btn.dataset.studentName}`;
                        if (activeEchoChannel) window.Echo.leave(activeEchoChannel);
                        loadHistory(chatId);
                        chatPopup.classList.add('active');
                        activeEchoChannel = `chat.${chatId}`;
                        window.Echo.private(activeEchoChannel).listen('.MessageSent', (e) => {
                            if (e.message.id_writer !== currentUserId)
                                appendMessage(e.message.contenu, 'incoming', `${e.message.writer.firstname} ${e.message.writer.name}`, e.message.created_at);
                        });
                    });
                });

                sendBtn.addEventListener('click', sendMessage);
                chatInput.addEventListener('keydown', e => { if (e.key === 'Enter') sendMessage(); });

                function sendMessage() {
                    if (!chatInput.value.trim() || !activeChatId) return;
                    const formData = new FormData();
                    formData.append('contenu', chatInput.value.trim());
                    formData.append('id_chat', activeChatId);
                    formData.append('_token', csrfToken);
                    fetch(`${baseUrl}/chat/store`, {
                        method: 'POST', body: formData,
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Socket-ID': window.Echo?.socketId() ?? '' }
                    }).then(r => r.json()).then(data => {
                        if (data.status === 'success') { appendMessage(data.message.contenu, 'outgoing', '', data.message.created_at); chatInput.value = ''; }
                    });
                }

                document.getElementById('close-chat-btn').addEventListener('click', () => chatPopup.classList.remove('active'));
            });
        </script>
    @endpush
@endsection
{{-- resources/views/tutor/backoffice.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="backoffice-container">
        <h2>Gérer les élèves</h2>

        <table class="students-table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Accès</th>
                    <th>Tuteurs</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($eleves as $eleve)
                    <tr>
                        <td>{{ $eleve->firstname }} {{ $eleve->name }}</td>
                        <td>{{ $eleve->access ?? 'Aucun Accès Privé' }}</td>
                        <td>{{ $eleve->tuteur?->firstname ?? 'Aucun Tuteur associé' }}</td>
                        <td>
                            @if($eleve->chats->isNotEmpty())
                                <button class="open-chat-btn" data-chat-id="{{ $eleve->chats->first()->id }}"
                                    data-student-name="{{ $eleve->firstname }} {{ $eleve->name }}">
                                    <i class='bx bx-message-square-dots'></i>
                                </button>
                            @else
                                <span>—</span>
                            @endif
                            <button class="delete-btn"><i class='bx bx-trash'></i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @include('components.chat')

    <script>document.addEventListener('DOMContentLoaded', () => {
            const chatPopup = document.getElementById('chat-popup');
            const chatMessagesContainer = document.getElementById('chat-messages-container');
            const chatInput = document.getElementById('chat-input');
            const sendBtn = document.getElementById('send-btn');
            const chatStudentName = document.getElementById('chat-student-name');
            const currentUserId = {{ Auth::id() }};
            const baseUrl = "{{ url('') }}";

            let activeChatId = null;
            let activeEchoChannel = null;

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const scrollToBottom = () => {
                chatMessagesContainer.scrollTop = chatMessagesContainer.scrollHeight;
            };

            // Construit une bulle de message avec nom/prénom
            function appendMessage(contenu, direction, writerName = '') {
                const messageDiv = document.createElement('div');
                messageDiv.classList.add('message', direction);

                const now = new Date();
                const timeStr = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`;

                messageDiv.innerHTML = `
                                                                    ${writerName ? `<span class="message-author">${writerName}</span>` : ''}
                                                                    <p>${contenu}</p>
                                                                    <span class="message-time">${timeStr}</span>
                                                                `;

                chatMessagesContainer.appendChild(messageDiv);
                scrollToBottom();
            }

            // Charge l'historique des messages d'un chat
            function loadHistory(chatId) {
                chatMessagesContainer.innerHTML = '<p class="loading">Chargement...</p>';

                fetch(`/tedycrpe/public/chat/${chatId}/messages`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                    .then(r => r.json())
                    .then(messages => {
                        chatMessagesContainer.innerHTML = '';
                        messages.forEach(msg => {
                            const direction = msg.id_writer === currentUserId ? 'outgoing' : 'incoming';
                            const writerName = msg.id_writer !== currentUserId
                                ? `${msg.writer.firstname} ${msg.writer.name}`
                                : '';
                            appendMessage(msg.contenu, direction, writerName);
                        });
                    });
            }

            // Ouvre le chat d'un élève au clic sur le bouton
            document.querySelectorAll('.open-chat-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const chatId = btn.dataset.chatId;
                    const studentName = btn.dataset.studentName;

                    activeChatId = chatId;
                    chatStudentName.textContent = `Discussion avec ${studentName}`;

                    // Quitte l'ancien canal Echo si existant
                    if (activeEchoChannel) {
                        window.Echo.leave(activeEchoChannel);
                    }

                    loadHistory(chatId);
                    chatPopup.classList.add('active');

                    // Rejoindre le canal Reverb du bon chat
                    activeEchoChannel = `chat.${chatId}`;
                    window.Echo.private(activeEchoChannel)
                        .listen('.MessageSent', (e) => {
                            if (e.message.id_writer !== currentUserId) {
                                const writerName = `${e.message.writer.firstname} ${e.message.writer.name}`;
                                appendMessage(e.message.contenu, 'incoming', writerName);
                            }
                        });
                });
            });

            // Envoi d'un message
            sendBtn.addEventListener('click', sendMessage);
            chatInput.addEventListener('keydown', e => { if (e.key === 'Enter') sendMessage(); });

            function sendMessage() {
                if (!chatInput.value.trim() || !activeChatId) return;

                const formData = new FormData();
                formData.append('contenu', chatInput.value.trim());
                formData.append('id_chat', activeChatId);
                formData.append('_token', csrfToken);

                fetch(`${baseUrl}/chat/store`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Socket-ID': window.Echo?.socketId() ?? ''
                    }
                })
                    .then(r => r.json())
                    .then(data => {
                        if (data.status === 'success') {
                            appendMessage(data.message.contenu, 'outgoing');
                            chatInput.value = '';
                        }
                    });
            }

            document.getElementById('close-chat-btn').addEventListener('click', () => {
                chatPopup.classList.remove('active');
            });
        });</script>
@endsection
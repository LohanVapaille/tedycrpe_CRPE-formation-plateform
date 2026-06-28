@extends('layouts.gabaritsansfooter')

@section('title', 'Mon Tableau de bord')

@section('content')
    <div class="dashboard-layout fade-in">

        <aside class="dashboard-sidebar">
            <div class="user-profile">
                <div class="avatar-placeholder">
                    👤
                </div>
                <h3 class="user-fullname">{{ Auth::user()->firstname }} {{ Auth::user()->name }}</h3>
            </div>

            <nav class="sidebar-menu">
                <a href="#tb" class="menu-item active">📊 Tableau de bord</a>
                <a href="{{ route('formation.cours', ['id' => 1]) }}" class="menu-item">📚 Les cours</a>
                <a href="#" class="menu-item">📹 Modules vidéos</a>

                <hr style="border: 0; border-top: 1px solid rgba(255,255,255,0.1); margin: 1rem 0;">
                <a href="{{ route('logout') }}" class="menu-item logout-item"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    style="color: #ff8b8b;">
                    🚪 Se déconnecter
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none" style="display: none;">
                    @csrf
                </form>
            </nav>

            <div class="tutor-section">
                <h4 class="sidebar-subtitle">Mon tuteur</h4>
                <div class="tutor-card">
                    <div class="tutor-avatar">👨‍🏫</div>
                    <div class="tutor-info">
                        <p class="tutor-name">Tedy Florette</p>
                        <p class="tutor-role">Formateur CRPE</p>
                    </div>
                    <!-- <a href="#" class="tutor-chat-btn"><i class='bx bxs-chat'></i></a> -->
                </div>
            </div>
        </aside>

        <main class="dashboard-main">

            <section class="welcome-banner">
                <div class="banner-left">
                    <h2>Bienvenue sur votre espace, {{ Auth::user()->firstname }}</h2>
                    <p class="meta-info">Inscrit à la formation depuis Septembre 2025</p>
                    <p class="meta-objective">Objectif : {{ $matieres->first()->module_associe ?? 'CRPE BAC + 5' }}</p>

                    <div class="banner-actions">
                        <a href="#" class="btn-secondary-light">Modifier le profil</a>
                        <a href="#" class="btn-orange">Besoin d'aide</a>
                    </div>
                </div>
                <div class="banner-right">
                    <div class="banner-right">
                        <span class="progress-label">Progression globale : {{ $progressionGlobale }}%</span>
                        <div class="progress-bar-container">
                            <div class="progress-bar-fill" style="width: {{ $progressionGlobale }}%"></div>
                        </div>
                    </div>
                </div>
            </section>

            <h3 id="tb" class="block-title">Tableau de bord</h3>
            <section class="stats-grid">
                <div class="stat-box">
                    <span class="stat-title">Avancement des chapitres</span>
                    <span class="stat-value">{{ $totalCoursTermines }} <span class="stat-separator">/</span>
                        {{ $totalCoursFormation }}</span>
                </div>
                <div class="stat-box">
                    <span class="stat-title">Fichiers & Devoirs</span>
                    <span class="stat-value">{{ $nombreFichiersEnvoyes }} fichiers envoyés</span>
                </div>
            </section>

            <h3 class="block-title">Le Flux d'Activité</h3>
            <section class="resume-activity-box">
                <p>Reprenez là où vous vous étiez arrêté !</p>
                <a href="{{ route('formation.cours', ['id' => 1]) }}" class="btn-dark-blue">Accéder à ma formation</a>
            </section>

            <div class="matieres-section-header">
                <p class="filter-label">Accéder au cours disponible :</p>
                <div class="filter-badges">
                    <span class="badge active">✓ Tous</span>
                    <span class="badge">Oraux</span>
                    <span class="badge">Écrit</span>
                </div>
            </div>

            <section id="cours" class="matieres-secure-grid">
                @forelse($matieres as $index => $matiere)
                    <div class="secure-matiere-card">
                        <span class="matiere-number">{{ $index + 1 }}. {{ $matiere->name }}</span>
                        <a href="{{ route('formation.cours', ['id' => $matiere->cours->first()->id]) }}"
                            class="btn-action-course">
                            Lire le cours
                        </a>
                        <span class="matiere-progression">{{ $matiere->progression ?? 0 }}% de progression</span>
                    </div>
                @empty
                    <p class="no-data">Aucune matière n'est disponible pour le moment.</p>
                @endforelse
            </section>

        </main>
    </div>

    @include('components.chat')

    <div class="tutor-chat-btn">
        <i class='bx bxs-message-rounded-dots'></i>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const currentUserId = {{ Auth::id() }};
            const chatId = {{ $chat->id }};
            const baseUrl = "{{ url('') }}";
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const chatPopup = document.getElementById('chat-popup');
            const chatMessagesContainer = document.getElementById('chat-messages-container');
            const chatInput = document.getElementById('chat-input');
            const sendBtn = document.getElementById('send-btn');

            const scrollToBottom = () => chatMessagesContainer.scrollTop = chatMessagesContainer.scrollHeight;

            // Ouvre le popup
            document.querySelector('.tutor-chat-btn').addEventListener('click', (e) => {
                e.preventDefault();
                chatPopup.classList.add('active');
                loadHistory(chatId);
            });

            document.getElementById('close-chat-btn').addEventListener('click', () => {
                chatPopup.classList.remove('active');
            });

            // Historique
            function loadHistory(chatId) {
                chatMessagesContainer.innerHTML = '';
                fetch(`${baseUrl}/chat/${chatId}/messages`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
                })
                    .then(r => r.json())
                    .then(messages => {
                        messages.forEach(msg => {
                            const direction = msg.id_writer === currentUserId ? 'outgoing' : 'incoming';
                            const writerName = direction === 'incoming' ? `${msg.writer.firstname} ${msg.writer.name}` : '';
                            appendMessage(msg.contenu, direction, writerName, msg.created_at);
                        });
                    });
            }

            // Envoi
            sendBtn.addEventListener('click', sendMessage);
            chatInput.addEventListener('keydown', e => { if (e.key === 'Enter') sendMessage(); });

            function sendMessage() {
                if (!chatInput.value.trim()) return;
                const formData = new FormData();
                formData.append('contenu', chatInput.value.trim());
                formData.append('id_chat', chatId);
                formData.append('_token', csrfToken);

                fetch(`${baseUrl}/chat/store`, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Socket-ID': window.Echo?.socketId() ?? '' }
                })
                    .then(r => r.json())
                    .then(data => {
                        if (data.status === 'success') {
                            appendMessage(data.message.contenu, 'outgoing', '', data.message.created_at);
                            chatInput.value = '';
                        }
                    });
            }

            // Bulle message
            function appendMessage(contenu, direction, writerName = '', createdAt = null) {
                const messageDiv = document.createElement('div');
                messageDiv.classList.add('message', direction);
                const timeStr = createdAt
                    ? new Date(createdAt).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })
                    : new Date().toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });

                messageDiv.innerHTML = `
                                    ${writerName ? `<span class="message-author">${writerName}</span>` : ''}
                                    <p>${contenu}</p>
                                    <span class="message-time">${timeStr}</span>
                                `;
                chatMessagesContainer.appendChild(messageDiv);
                scrollToBottom();
            }

            // Écoute Reverb
            const waitForEcho = setInterval(() => {
                if (typeof window.Echo !== 'undefined') {
                    clearInterval(waitForEcho);
                    window.Echo.private(`chat.${chatId}`)
                        .listen('.MessageSent', (e) => {
                            if (e.message.id_writer !== currentUserId) {
                                appendMessage(e.message.contenu, 'incoming', `${e.message.writer.firstname} ${e.message.writer.name}`, e.message.created_at);
                            }
                        });
                }
            }, 200);
        });
    </script>
@endsection
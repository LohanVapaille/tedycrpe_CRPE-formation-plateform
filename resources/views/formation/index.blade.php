@extends('layouts.gabaritsansfooter')

@section('title', 'Mon Tableau de bord')

@section('content')

    <div class="dashboard-layout fade-in">

        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        <button class="dashboard-toggle-btn" id="sidebarToggle">
            <i class='bx bxs-info-circle'></i>
        </button>
        <aside class="dashboard-sidebar" id="dashboardSidebar">

            <div class="user-profile">
                <div class="avatar-placeholder">👤</div>
                <h3 class="user-fullname">{{ Auth::user()->firstname }} {{ Auth::user()->name }}</h3>
            </div>

            <nav class="sidebar-menu">
                <a href="#tb" class="menu-item active"><i class='bx bxs-dashboard'></i> Tableau de bord</a>
                <a href="{{ route('formation.cours', ['id' => 1]) }}" class="menu-item"><i class='bx bx-book-open'></i> Les
                    cours</a>
                <a href="#" class="menu-item"><i class='bx bx-video'></i> Modules vidéos</a>

                <hr style="border: 0; border-top: 1px solid rgba(255,255,255,0.1); margin: 1rem 0;">
                <a href="{{ route('logout') }}" class="menu-item logout-item"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    style="color: #ff8b8b;">
                    <i class='bx bx-door-open'></i> Se déconnecter
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </nav>

            {{-- On affiche l'encadré "Mon Tuteur" uniquement si l'utilisateur connecté est un élève --}}
            @if(!Auth::user()->is_tutor)
                <div class="tutor-section">
                    <h4 class="sidebar-subtitle">Mon tuteur</h4>
                    <div class="tutor-card">
                        <div class="tutor-avatar">👨‍🏫</div>
                        <div class="tutor-info">
                            <p class="tutor-name">Tedy Florette</p>
                            <p class="tutor-role">Formateur CRPE</p>
                        </div>
                    </div>
                </div>
            @endif
        </aside>

        <main class="dashboard-main">

            <section class="welcome-banner">
                <div class="banner-left">
                    <h2>Bienvenue sur votre espace, {{ Auth::user()->firstname }}</h2>
                    <p class="meta-info">Inscrit à la formation depuis Septembre 2025</p>
                    <p class="meta-objective">Objectif : {{ Auth::user()->access }}</p>

                    <div class="banner-actions">
                        <a href="#" class="btn-secondary-light">Modifier le profil</a>
                        <a href="#" class="btn-orange">Besoin d'aide</a>
                    </div>
                </div>
                <div class="banner-right">
                    <span class="progress-label">Progression globale : {{ $progressionGlobale }}%</span>
                    <div class="progress-bar-container">
                        <div class="progress-bar-fill" style="width: {{ $progressionGlobale }}%"></div>
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
            </div>

            <section id="cours" class="matieres-secure-grid">
                @forelse($matieres as $index => $matiere)
                    <div class="secure-matiere-card">
                        <span class="matiere-number">{{ $index + 1 }}. {{ $matiere->name }}</span>
                        @if($matiere->cours->isNotEmpty())
                            <a href="{{ route('formation.cours', ['id' => $matiere->cours->first()->id]) }}"
                                class="btn-action-course">
                                Lire le cours
                            </a>
                        @else
                            <span class="btn-action-course" style="opacity:0.5; cursor:not-allowed;">Bientôt disponible</span>
                        @endif
                        <span class="matiere-progression">{{ $matiere->progression ?? 0 }}% de progression</span>
                    </div>
                @empty
                    <p class="no-data">Aucune matière n'est disponible pour le moment.</p>
                @endforelse
            </section>

        </main>
    </div>

    @include('components.chat')

    @if(Auth::user()->is_tutor)
        {{-- Bouton pour le TUTEUR --}}
        <div class="tutor-channels-btn" id="open-channels-btn"
            style="position: fixed; bottom: 20px; right: 20px; background: #2c6e7a; color: white; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; cursor: pointer; z-index: 999; box-shadow: 0 4px 10px rgba(0,0,0,0.2);">
            <i class='bx bxs-chat'></i>
        </div>
    @else
        {{-- Bouton pour l'ÉLÈVE --}}
        <div class="tutor-chat-btn">
            <i class='dashboard-toggle-btn-i bx bxs-message-rounded-dots'></i>
        </div>
    @endif

    {{-- Fenêtre Popup de la liste des canaux (Uniquement visible/générée pour le Tuteur) --}}
    @if(Auth::user()->is_tutor)
        <div id="channels-popup" class="chat-popup"
            style="display: none; position: fixed; bottom: 90px; right: 20px; width: 350px; height: 450px; background: white; border-radius: 12px; box-shadow: 0 5px 25px rgba(0,0,0,0.15); z-index: 1000; flex-direction: column; overflow: hidden;">
            <div class="chat-header"
                style="background: #2c6e7a; color: white; padding: 1rem; display: flex; justify-content: space-between; align-items: center;">
                <h4 style="margin: 0; font-size: 1rem;"><i class='bx bxs-user-detail'></i> Mes canaux élèves</h4>
                <button id="close-channels-btn"
                    style="background: none; border: none; color: white; font-size: 1.5rem; cursor: pointer;">&times;</button>
            </div>
            <div class="channels-body" id="channels-list-container" style="flex: 1; overflow-y: auto; padding: 0.5rem;">
                <p style="text-align: center; color: gray; margin-top: 2rem;">Chargement des conversations...</p>
            </div>
        </div>
    @endif

    {{-- Script de gestion de la sidebar responsive --}}
    <script>
        const sidebarToggle = document.getElementById('sidebarToggle');
        const dashboardSidebar = document.getElementById('dashboardSidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        sidebarToggle?.addEventListener('click', () => {
            dashboardSidebar.classList.toggle('open');
            sidebarOverlay.classList.toggle('active');
            document.body.classList.toggle('no-scroll');
            const isOpen = dashboardSidebar.classList.contains('open');
            sidebarToggle.style.left = isOpen ? '248px' : '0';

            const icon = sidebarToggle.querySelector('i');
            if (isOpen) {
                icon.classList.remove('bxs-info-circle');
                icon.classList.add('bx-x');
            } else {
                icon.classList.remove('bx-x');
                icon.classList.add('bxs-info-circle');
            }
        });

        sidebarOverlay?.addEventListener('click', () => {
            dashboardSidebar.classList.remove('open');
            sidebarOverlay.classList.remove('active');
            document.body.classList.remove('no-scroll');
            sidebarToggle.style.left = '0';

            const icon = sidebarToggle.querySelector('i');
            icon.classList.remove('bx-x');
            icon.classList.add('bxs-info-circle');
        });
    </script>

    {{-- Script Global du chat (Élève + Tuteur) --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const currentUserId = {{ Auth::id() }};
            const isTuteur = {{ Auth::user()->is_tutor ? 'true' : 'false' }};
            let activeChatId = isTuteur ? null : {{ $chat->id ?? 'null' }};

            const baseUrl = "{{ url('') }}";
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const chatPopup = document.getElementById('chat-popup');
            const chatMessagesContainer = document.getElementById('chat-messages-container');
            const chatInput = document.getElementById('chat-input');
            const sendBtn = document.getElementById('send-btn');
            const chatStudentName = document.getElementById('chat-student-name');

            const scrollToBottom = () => chatMessagesContainer.scrollTop = chatMessagesContainer.scrollHeight;

            /* ==========================================
               FONCTIONS COMMUNES (Accessibles par tous)
               ========================================== */
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
                            appendMessage(data.message.contenu, 'outgoing', '', data.message.created_at);
                            chatInput.value = '';
                        }
                    });
            }

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

            let currentEchoChannel = null;
            function listenToChannel(chatId) {
                if (typeof window.Echo === 'undefined') return;

                if (currentEchoChannel) {
                    window.Echo.leave(`chat.${currentEchoChannel}`);
                }

                currentEchoChannel = chatId;
                window.Echo.private(`chat.${chatId}`)
                    .listen('.MessageSent', (e) => {
                        if (e.message.id_writer !== currentUserId) {
                            appendMessage(e.message.contenu, 'incoming', `${e.message.writer.firstname} ${e.message.writer.name}`, e.message.created_at);
                        }
                    });
            }

            // Événements liés à la fenêtre de discussion principale (communs)
            document.getElementById('close-chat-btn')?.addEventListener('click', () => {
                chatPopup.classList.remove('active');
                chatPopup.style.display = 'none';
            });

            sendBtn?.addEventListener('click', sendMessage);
            chatInput?.addEventListener('keydown', e => { if (e.key === 'Enter') sendMessage(); });


            /* ==========================================
               LOGIQUE APPRENANT / ÉLÈVE
               ========================================== */
            if (!isTuteur) {
                document.querySelector('.tutor-chat-btn')?.addEventListener('click', (e) => {
                    e.preventDefault();
                    chatPopup.classList.add('active');
                    chatPopup.style.display = 'flex';
                    if (activeChatId) loadHistory(activeChatId);
                });

                if (activeChatId) {
                    listenToChannel(activeChatId);
                }
            }

            /* ==========================================
               LOGIQUE TUTEUR (Gestion des canaux)
               ========================================== */
            if (isTuteur) {
                const channelsPopup = document.getElementById('channels-popup');

                document.getElementById('open-channels-btn')?.addEventListener('click', (e) => {
                    e.preventDefault();
                    channelsPopup.classList.add('active');
                    channelsPopup.style.display = 'flex';
                    loadChannels();
                });

                document.getElementById('close-channels-btn')?.addEventListener('click', (e) => {
                    e.preventDefault();
                    channelsPopup.classList.remove('active');
                    channelsPopup.style.display = 'none';
                });

                function loadChannels() {
                    const channelsListContainer = document.getElementById('channels-list-container');
                    if (!channelsListContainer) return;

                    channelsListContainer.innerHTML = '<p style="text-align: center; color: gray; margin-top: 2rem; font-family: sans-serif;">Chargement...</p>';

                    fetch(`${baseUrl}/tuteur/chats`, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                    })
                        .then(r => {
                            if (!r.ok) throw new Error("Réponse serveur incorrecte status " + r.status);
                            return r.json();
                        })
                        .then(chats => {
                            channelsListContainer.innerHTML = '';

                            if (!chats || chats.length === 0) {
                                channelsListContainer.innerHTML = '<p style="text-align: center; color: gray; margin-top: 2rem; font-family: sans-serif;">Aucune conversation trouvée.</p>';
                                return;
                            }

                            chats.forEach(c => {
                                const student = c.user;
                                if (!student) return;

                                const item = document.createElement('div');
                                item.style.cssText = "display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; border-bottom: 1px solid #f0f0f0; cursor: pointer; transition: background 0.2s;";
                                item.innerHTML = `
                                    <div style="font-size: 1.5rem;">👤</div>
                                    <div style="flex: 1; font-family: sans-serif;">
                                        <p style="margin: 0; font-weight: 600; font-size: 0.9rem; color: #333;">${student.firstname} ${student.name}</p>
                                        <p style="margin: 0; font-size: 0.75rem; color: gray; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            ${c.latest_message ? c.latest_message.contenu : 'Pas de message'}
                                        </p>
                                    </div>
                                `;

                                item.addEventListener('click', () => {
                                    channelsPopup.classList.remove('active');
                                    channelsPopup.style.display = 'none';

                                    if (chatStudentName) chatStudentName.textContent = `Discussion avec ${student.firstname} ${student.name}`;
                                    activeChatId = c.id;

                                    if (chatPopup) {
                                        chatPopup.classList.add('active');
                                        chatPopup.style.display = 'flex';
                                        loadHistory(c.id);
                                        listenToChannel(c.id);
                                    }
                                });
                                channelsListContainer.appendChild(item);
                            });
                        })
                        .catch(err => {
                            console.error("Erreur lors du fetch des canaux :", err);
                            channelsListContainer.innerHTML = '<p style="text-align: center; color: red; margin-top: 2rem; font-family: sans-serif;">Erreur de chargement.</p>';
                        });
                }
            }
        }); // Fin du DOMContentLoaded (Évite l'erreur de parenthèse inattendue)
    </script>
@endsection
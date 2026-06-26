<div id="chat-popup" class="chat-popup">
    <div class="chat-header">
        <div class="tutor-info">
            <span class="tutor-status online"></span>
            <h4>Discussion avec mon tuteur</h4>
        </div>
        <button id="close-chat-btn" class="close-chat-btn">&times;</button>
    </div>

    <div class="chat-body" id="chat-messages-container">
        <div class="message incoming">
            <p>Bonjour ! Comment puis-je t'aider aujourd'hui ?</p>
            <span class="message-time">10:41</span>
        </div>
    </div>

    <form id="chat-form" action="{{ route('chat.store') }}" method="POST" class="chat-footer">
        @csrf
        <input type="hidden" name="id_chat" value="{{ $chat->id }}">
        <input type="text" name="contenu" id="chat-input" placeholder="Écrivez votre message..." required
            autocomplete="off">
        <button type="submit" class="send-message-btn"><i class='bx bxs-send'></i></button>
    </form>
</div>

<div class="tutor-chat-btn">
    <i class='bx bxs-message-rounded-dots'></i>
</div>



<script>document.addEventListener('DOMContentLoaded', () => {
        const openChatBtn = document.querySelector('.tutor-chat-btn');
        const closeChatBtn = document.getElementById('close-chat-btn');
        const chatPopup = document.getElementById('chat-popup');
        const chatMessagesContainer = document.getElementById('chat-messages-container');
        const chatForm = document.getElementById('chat-form');
        const chatInput = document.getElementById('chat-input');

        // On injecte dynamiquement l'ID de l'utilisateur connecté via Blade
        const currentUserId = {{ Auth::id() }};
        // On récupère dynamiquement l'ID de la table chat (ex: 1) passé par l'input caché de ton formulaire
        const chatId = {{ $chat->id }};  // Plus besoin de lire le DOM

        // Fonction utilitaire pour scroller automatiquement au dernier message
        const scrollToBottom = () => {
            chatMessagesContainer.scrollTop = chatMessagesContainer.scrollHeight;
        };

        // Gestion de l'affichage du Popup
        if (openChatBtn) {
            openChatBtn.addEventListener('click', (e) => {
                e.preventDefault();
                chatPopup.classList.add('active');
                scrollToBottom();
            });
        }

        if (closeChatBtn) {
            closeChatBtn.addEventListener('click', () => {
                chatPopup.classList.remove('active');
            });
        }

        // 1. INTERCEPTION DE L'ENVOI (AJAX)
        chatForm.addEventListener('submit', function (e) {
            e.preventDefault(); // Bloque le rechargement de page

            if (!chatInput.value.trim()) return;

            const formData = new FormData(this);

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // On affiche directement notre propre message à droite (outgoing)
                        appendMessage(data.message.contenu, 'outgoing');
                        chatInput.value = ''; // On vide le champ texte
                    }
                })
                .catch(err => console.error("Erreur lors de l'envoi :", err));
        });

        // Fonction utilitaire pour injecter une bulle HTML dans le chat
        function appendMessage(contenu, direction) {
            const messageDiv = document.createElement('div');
            messageDiv.classList.add('message', direction);

            const now = new Date();
            const timeStr = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`;

            messageDiv.innerHTML = `
            <p>${contenu}</p>
            <span class="message-time">${timeStr}</span>
        `;

            chatMessagesContainer.appendChild(messageDiv);
            scrollToBottom();
        }

        // 2. ÉCOUTE DE REVERB (Avec détection dynamique)
        const waitForEcho = setInterval(() => {
            if (typeof window.Echo !== 'undefined') {
                clearInterval(waitForEcho); // C'est bon, Echo est chargé ! On arrête la boucle.

                console.log("🚀 Laravel Echo est prêt, connexion au canal privé...");

                window.Echo.private(`chat.${chatId}`)
                    .listen('.MessageSent', (e) => {  // <-- Ajoute bien le point "." ici devant MessageSent
                        console.log("✉️ Message reçu en direct :", e);
                        if (e.message.id_writer !== currentUserId) {
                            appendMessage(e.message.contenu, 'incoming');
                        }
                    });
            }
        }, 200); // Il vérifie toutes les 200ms si Echo est apparu
    });
</script>
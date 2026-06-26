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
        <input type="hidden" name="id_chat" value="1">
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

        // Ouvrir le chat au clic
        if (openChatBtn) {
            openChatBtn.addEventListener('click', (e) => {
                e.preventDefault(); // Empêche le saut de page du lien #
                chatPopup.classList.add('active');
                // Auto-scroll tout en bas pour voir les derniers messages
                chatMessagesContainer.scrollTop = chatMessagesContainer.scrollHeight;
            });
        }

        // Fermer le chat au clic sur la croix
        if (closeChatBtn) {
            closeChatBtn.addEventListener('click', () => {
                chatPopup.classList.remove('active');
            });
        }
    });</script>
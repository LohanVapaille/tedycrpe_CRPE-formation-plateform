<div id="chat-popup" class="chat-popup">
    <div class="chat-header">
        <div class="tutor-info">
            <span class="tutor-status online"></span>
            <h4 id="chat-student-name">Discussion avec mon tuteur</h4>
        </div>
        <button id="close-chat-btn" class="close-chat-btn">&times;</button>
    </div>

    {{-- Message d'accueil côté élève (délai de réponse à confirmer avec Tedy) --}}
    @unless(Auth::user()->is_tutor)
        <div class="chat-welcome">
            <i class='bx bx-time-five'></i>
            <p>Bonjour ! Posez ici vos questions sur la formation. Tedy vous répond sous <strong>48 h</strong> (jours ouvrés).</p>
        </div>
    @endunless

    <div class="chat-body" id="chat-messages-container"></div>

    <div class="chat-footer">
        <input type="text" id="chat-input" placeholder="Écrivez votre message..." autocomplete="off">
        <button id="send-btn" class="send-message-btn"><i class='bx bxs-send'></i></button>
    </div>
</div>
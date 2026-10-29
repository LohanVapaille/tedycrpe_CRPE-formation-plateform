document.addEventListener("DOMContentLoaded", () => {
    if (!window.chatConfig) return; // pas connecté, on arrête tout

    const { currentUserId, isTuteur, baseUrl, csrfToken } = window.chatConfig;
    let activeChatId = isTuteur ? null : window.chatConfig.initialChatId;

    const chatPopup = document.getElementById("chat-popup");
    const chatMessagesContainer = document.getElementById("chat-messages-container");
    const chatInput = document.getElementById("chat-input");
    const sendBtn = document.getElementById("send-btn");
    const chatStudentName = document.getElementById("chat-student-name");
    const channelsPopup = document.getElementById("channels-popup");
    const badge = document.getElementById(isTuteur ? "channels-notif-badge" : "chat-notif-badge");

    // Non lus : la base de données fait foi, le temps réel ne fait qu'accélérer la mise à jour
    let unreadTotal = window.chatConfig.initialUnread || 0;

    const POLL_CONNECTED_MS = 60000;
    const POLL_FALLBACK_MS = 15000;

    const jsonHeaders = {
        "X-Requested-With": "XMLHttpRequest",
        Accept: "application/json",
        "X-CSRF-TOKEN": csrfToken,
    };

    const escapeHtml = (str) =>
        String(str ?? "").replace(/[&<>"']/g, (c) => ({ "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;" })[c]);

    const fullName = (u) => (u ? `${u.firstname ?? ""} ${u.name ?? ""}`.trim() : "");

    const isRealtimeConnected = () => window.Echo?.connector?.pusher?.connection?.state === "connected";

    const isChatOpen = (chatId) =>
        chatPopup && chatPopup.classList.contains("active") && activeChatId == chatId;

    const isChannelsOpen = () => channelsPopup && channelsPopup.classList.contains("active");

    const scrollToBottom = () => {
        if (chatMessagesContainer) chatMessagesContainer.scrollTop = chatMessagesContainer.scrollHeight;
    };

    function updateBadge() {
        if (!badge) return;
        badge.textContent = unreadTotal > 9 ? "9+" : unreadTotal;
        badge.style.display = unreadTotal > 0 ? "flex" : "none";
    }

    function appendMessage(contenu, direction, writerName = "", createdAt = null) {
        if (!chatMessagesContainer) return;
        const messageDiv = document.createElement("div");
        messageDiv.classList.add("message", direction);
        const timeStr = (createdAt ? new Date(createdAt) : new Date()).toLocaleTimeString("fr-FR", {
            hour: "2-digit",
            minute: "2-digit",
        });

        messageDiv.innerHTML = `
            ${writerName ? `<span class="message-author">${escapeHtml(writerName)}</span>` : ""}
            <p>${escapeHtml(contenu)}</p>
            <span class="message-time">${timeStr}</span>
        `;
        chatMessagesContainer.appendChild(messageDiv);
        scrollToBottom();
    }

    // Charge l'historique ; côté serveur, cela marque aussi les messages reçus comme lus
    function loadHistory(chatId) {
        if (!chatMessagesContainer) return;
        fetch(`${baseUrl}/chat/${chatId}/messages`, { headers: jsonHeaders })
            .then((r) => r.json())
            .then((messages) => {
                chatMessagesContainer.innerHTML = "";
                messages.forEach((msg) => {
                    const direction = msg.id_writer === currentUserId ? "outgoing" : "incoming";
                    appendMessage(msg.contenu, direction, direction === "incoming" ? fullName(msg.writer) : "", msg.created_at);
                });
                refreshUnread();
            });
    }

    function markRead(chatId) {
        fetch(`${baseUrl}/chat/${chatId}/read`, { method: "POST", headers: jsonHeaders }).then(refreshUnread);
    }

    function sendMessage() {
        if (!chatInput || !chatInput.value.trim() || !activeChatId) return;

        const formData = new FormData();
        formData.append("contenu", chatInput.value.trim());
        formData.append("id_chat", activeChatId);

        sendBtn && (sendBtn.disabled = true);

        fetch(`${baseUrl}/chat/store`, {
            method: "POST",
            body: formData,
            headers: { ...jsonHeaders, "X-Socket-ID": window.Echo?.socketId() ?? "" },
        })
            .then((r) => r.json())
            .then((data) => {
                if (data.status === "success") {
                    appendMessage(data.message.contenu, "outgoing", "", data.message.created_at);
                    chatInput.value = "";
                    refreshUnread();
                }
            })
            .finally(() => sendBtn && (sendBtn.disabled = false));
    }

    function showToast(authorName, message, chatId) {
        const toast = document.createElement("div");
        toast.className = "toast-notif";
        toast.innerHTML = `
            <div class="toast-icon">💬</div>
            <div class="toast-content">
                <strong>${escapeHtml(authorName)}</strong>
                <p>${escapeHtml(message)}</p>
            </div>
        `;
        document.body.appendChild(toast);

        toast.addEventListener("click", () => {
            toast.remove();
            openChat(chatId, authorName);
        });

        setTimeout(() => {
            toast.classList.add("hide");
            setTimeout(() => toast.remove(), 300);
        }, 5000);
    }

    function openChat(chatId, authorName = "") {
        if (!chatPopup) {
            // Le popup n'existe pas sur cette page → on redirige vers le dashboard
            sessionStorage.setItem("open_chat_id", chatId);
            sessionStorage.setItem("open_chat_author", authorName);
            window.location.href = `${baseUrl}/formation?open_chat=${chatId}`;
            return;
        }

        activeChatId = chatId;

        if (isTuteur && chatStudentName && authorName) {
            chatStudentName.textContent = `Discussion avec ${authorName}`;
        }

        if (isChannelsOpen()) closeChannels();
        chatPopup.classList.add("active");
        chatPopup.style.display = "flex";
        loadHistory(chatId);
    }

    function onIncomingMessage(e) {
        const msg = e.message;
        if (msg.id_writer === currentUserId) return;

        const authorName = fullName(msg.writer);

        if (isChatOpen(msg.id_chat)) {
            appendMessage(msg.contenu, "incoming", authorName, msg.created_at);
            markRead(msg.id_chat);
            return;
        }

        unreadTotal++;
        updateBadge();
        showToast(authorName, msg.contenu, msg.id_chat);
        if (isChannelsOpen()) loadChannels();
    }

    /* Filet de sécurité : relit les non lus en base (connexion temps réel coupée, onglet en veille…) */
    let pollTimer = null;

    function refreshUnread() {
        return fetch(`${baseUrl}/chat/unread`, { headers: jsonHeaders })
            .then((r) => (r.ok ? r.json() : null))
            .then((data) => {
                if (!data) return;
                const previous = unreadTotal;
                unreadTotal = data.total;
                updateBadge();

                if (unreadTotal > previous && !isRealtimeConnected()) {
                    // Nouveau message détecté sans le temps réel : on rafraîchit ce qui est affiché
                    if (activeChatId && data.chats[activeChatId] && isChatOpen(activeChatId)) {
                        loadHistory(activeChatId);
                    } else if (isChannelsOpen()) {
                        loadChannels();
                    }
                }
            })
            .catch(() => {});
    }

    function schedulePoll() {
        clearTimeout(pollTimer);
        pollTimer = setTimeout(() => {
            if (!document.hidden) refreshUnread();
            schedulePoll();
        }, isRealtimeConnected() ? POLL_CONNECTED_MS : POLL_FALLBACK_MS);
    }

    document.addEventListener("visibilitychange", () => {
        if (!document.hidden) refreshUnread();
    });

    /* Temps réel */
    if (window.Echo) {
        if (isTuteur) {
            // Un seul canal pour tous les élèves (au lieu d'un abonnement par conversation)
            window.Echo.private("tutor").listen(".MessageSent", onIncomingMessage);
        } else if (activeChatId) {
            window.Echo.private(`chat.${activeChatId}`).listen(".MessageSent", onIncomingMessage);
        }

        // Au retour de connexion, on resynchronise ce qui a pu être manqué
        window.Echo.connector?.pusher?.connection?.bind("connected", refreshUnread);
    }

    updateBadge();
    schedulePoll();

    /* Événements communs (seulement si le popup existe sur cette page) */
    document.getElementById("close-chat-btn")?.addEventListener("click", () => {
        chatPopup.classList.remove("active");
        chatPopup.style.display = "none";
    });

    sendBtn?.addEventListener("click", sendMessage);
    chatInput?.addEventListener("keydown", (e) => {
        if (e.key === "Enter") sendMessage();
    });

    /* ÉLÈVE */
    if (!isTuteur) {
        document.querySelector(".tutor-chat-btn")?.addEventListener("click", (e) => {
            e.preventDefault();
            if (activeChatId) openChat(activeChatId);
        });
    }

    /* TUTEUR */
    function closeChannels() {
        channelsPopup.classList.remove("active");
        channelsPopup.style.display = "none";
    }

    function loadChannels() {
        const channelsListContainer = document.getElementById("channels-list-container");
        if (!channelsListContainer) return;

        if (!channelsListContainer.children.length) {
            channelsListContainer.innerHTML = '<p class="channels-empty">Chargement...</p>';
        }

        fetch(`${baseUrl}/tuteur/chats`, { headers: jsonHeaders })
            .then((r) => {
                if (!r.ok) throw new Error("Réponse serveur incorrecte status " + r.status);
                return r.json();
            })
            .then((chats) => {
                channelsListContainer.innerHTML = "";

                // Le serveur trie déjà : conversations avec non lus en haut, puis les plus récentes
                const visibles = chats.filter((c) => c.user);
                if (!visibles.length) {
                    channelsListContainer.innerHTML = '<p class="channels-empty">Aucune conversation trouvée.</p>';
                    return;
                }

                visibles.forEach((c) => {
                    const studentName = fullName(c.user);
                    const unread = c.unread_count || 0;

                    const item = document.createElement("div");
                    item.className = "channel-item" + (unread ? " has-unread" : "");
                    item.innerHTML = `
                        <div class="channel-avatar">👤</div>
                        <div class="channel-info">
                            <p class="channel-name">${escapeHtml(studentName)}</p>
                            <p class="channel-preview">${c.latest_message ? escapeHtml(c.latest_message.contenu) : "Pas de message"}</p>
                        </div>
                        ${unread ? `<span class="channel-unread">${unread > 9 ? "9+" : unread}</span>` : ""}
                    `;

                    item.addEventListener("click", () => openChat(c.id, studentName));
                    channelsListContainer.appendChild(item);
                });
            })
            .catch((err) => {
                console.error("Erreur lors du fetch des canaux :", err);
                channelsListContainer.innerHTML = '<p class="channels-empty is-error">Erreur de chargement.</p>';
            });
    }

    if (isTuteur) {
        document.getElementById("open-channels-btn")?.addEventListener("click", (e) => {
            e.preventDefault();
            channelsPopup.classList.add("active");
            channelsPopup.style.display = "flex";
            loadChannels();
        });

        document.getElementById("close-channels-btn")?.addEventListener("click", (e) => {
            e.preventDefault();
            closeChannels();
        });
    }

    /* Ouverture automatique après redirection depuis une notif */
    const urlParams = new URLSearchParams(window.location.search);
    const openChatParam = urlParams.get("open_chat");
    const storedChatId = sessionStorage.getItem("open_chat_id");

    if (openChatParam || storedChatId) {
        const chatIdToOpen = parseInt(openChatParam || storedChatId);
        const authorName = sessionStorage.getItem("open_chat_author") || "";
        sessionStorage.removeItem("open_chat_id");
        sessionStorage.removeItem("open_chat_author");

        if (openChatParam) window.history.replaceState({}, "", window.location.pathname);
        if (chatPopup) openChat(chatIdToOpen, authorName);
    }
});

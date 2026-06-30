document.addEventListener("DOMContentLoaded", () => {
    if (!window.chatConfig) return; // pas connecté, on arrête tout

    const { currentUserId, isTuteur, baseUrl, csrfToken } = window.chatConfig;
    let activeChatId = isTuteur ? null : window.chatConfig.initialChatId;

    const chatPopup = document.getElementById("chat-popup");
    const chatMessagesContainer = document.getElementById(
        "chat-messages-container",
    );
    const chatInput = document.getElementById("chat-input");
    const sendBtn = document.getElementById("send-btn");
    const chatStudentName = document.getElementById("chat-student-name");
    const chatNotifBadge = document.getElementById("chat-notif-badge");
    const channelsNotifBadge = document.getElementById("channels-notif-badge");

    let unreadCount = 0;
    let currentEchoChannel = null;
    const listenedChannels = new Set();

    const scrollToBottom = () => {
        if (chatMessagesContainer)
            chatMessagesContainer.scrollTop =
                chatMessagesContainer.scrollHeight;
    };

    function loadHistory(chatId) {
        if (!chatMessagesContainer) return;
        chatMessagesContainer.innerHTML = "";
        fetch(`${baseUrl}/chat/${chatId}/messages`, {
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                Accept: "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
        })
            .then((r) => r.json())
            .then((messages) => {
                messages.forEach((msg) => {
                    const direction =
                        msg.id_writer === currentUserId
                            ? "outgoing"
                            : "incoming";
                    const writerName =
                        direction === "incoming"
                            ? `${msg.writer.firstname} ${msg.writer.name}`
                            : "";
                    appendMessage(
                        msg.contenu,
                        direction,
                        writerName,
                        msg.created_at,
                    );
                });
            });
    }

    function sendMessage() {
        if (!chatInput || !chatInput.value.trim() || !activeChatId) return;

        const formData = new FormData();
        formData.append("contenu", chatInput.value.trim());
        formData.append("id_chat", activeChatId);
        formData.append("_token", csrfToken);

        fetch(`${baseUrl}/chat/store`, {
            method: "POST",
            body: formData,
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                Accept: "application/json",
                "X-CSRF-TOKEN": csrfToken,
                "X-Socket-ID": window.Echo?.socketId() ?? "",
            },
        })
            .then((r) => r.json())
            .then((data) => {
                if (data.status === "success") {
                    appendMessage(
                        data.message.contenu,
                        "outgoing",
                        "",
                        data.message.created_at,
                    );
                    chatInput.value = "";
                }
            });
    }

    function appendMessage(
        contenu,
        direction,
        writerName = "",
        createdAt = null,
    ) {
        if (!chatMessagesContainer) return;
        const messageDiv = document.createElement("div");
        messageDiv.classList.add("message", direction);
        const timeStr = createdAt
            ? new Date(createdAt).toLocaleTimeString("fr-FR", {
                  hour: "2-digit",
                  minute: "2-digit",
              })
            : new Date().toLocaleTimeString("fr-FR", {
                  hour: "2-digit",
                  minute: "2-digit",
              });

        messageDiv.innerHTML = `
            ${writerName ? `<span class="message-author">${writerName}</span>` : ""}
            <p>${contenu}</p>
            <span class="message-time">${timeStr}</span>
        `;
        chatMessagesContainer.appendChild(messageDiv);
        scrollToBottom();
    }

    function showToast(authorName, message, chatId) {
        const toast = document.createElement("div");
        toast.className = "toast-notif";
        toast.innerHTML = `
            <div class="toast-icon">💬</div>
            <div class="toast-content">
                <strong>${authorName}</strong>
                <p>${message}</p>
            </div>
        `;
        document.body.appendChild(toast);

        toast.addEventListener("click", () => {
            toast.remove();
            openChatFromNotif(chatId, authorName);
        });

        setTimeout(() => {
            toast.classList.add("hide");
            setTimeout(() => toast.remove(), 300);
        }, 5000);
    }

    function updateBadge() {
        const badge = isTuteur ? channelsNotifBadge : chatNotifBadge;
        if (!badge) return;

        if (unreadCount > 0) {
            badge.textContent = unreadCount > 9 ? "9+" : unreadCount;
            badge.style.display = "flex";
        } else {
            badge.style.display = "none";
        }
    }

    function openChatFromNotif(chatId, authorName = "") {
        const hasChatUI = document.getElementById("chat-popup");

        if (!hasChatUI) {
            // Le popup n'existe pas sur cette page → on redirige vers le dashboard
            sessionStorage.setItem("open_chat_id", chatId);
            sessionStorage.setItem("open_chat_author", authorName);
            window.location.href = `${baseUrl}/formation?open_chat=${chatId}`;
            return;
        }

        activeChatId = chatId;
        currentEchoChannel = chatId;

        if (isTuteur && chatStudentName && authorName) {
            chatStudentName.textContent = `Discussion avec ${authorName}`;
        }

        chatPopup.classList.add("active");
        chatPopup.style.display = "flex";
        loadHistory(chatId);

        unreadCount = 0;
        updateBadge();
    }

    function listenToChannel(chatId) {
        if (typeof window.Echo === "undefined") return;
        if (listenedChannels.has(chatId)) return;

        listenedChannels.add(chatId);

        window.Echo.private(`chat.${chatId}`).listen(".MessageSent", (e) => {
            if (e.message.id_writer === currentUserId) return;

            const authorName = `${e.message.writer.firstname} ${e.message.writer.name}`;
            const isThisChatOpen =
                chatPopup &&
                chatPopup.classList.contains("active") &&
                currentEchoChannel === chatId;

            if (isThisChatOpen) {
                appendMessage(
                    e.message.contenu,
                    "incoming",
                    authorName,
                    e.message.created_at,
                );
            } else {
                unreadCount++;
                updateBadge();
                showToast(authorName, e.message.contenu, chatId);
            }
        });
    }

    // Événements communs (seulement si le popup existe sur cette page)
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
        document
            .querySelector(".tutor-chat-btn")
            ?.addEventListener("click", (e) => {
                e.preventDefault();
                chatPopup.classList.add("active");
                chatPopup.style.display = "flex";
                currentEchoChannel = activeChatId;
                if (activeChatId) loadHistory(activeChatId);
                unreadCount = 0;
                updateBadge();
            });

        if (activeChatId) {
            listenToChannel(activeChatId);
        }
    }

    /* TUTEUR */
    if (isTuteur) {
        const channelsPopup = document.getElementById("channels-popup");

        document
            .getElementById("open-channels-btn")
            ?.addEventListener("click", (e) => {
                e.preventDefault();
                channelsPopup.classList.add("active");
                channelsPopup.style.display = "flex";
                loadChannels();
            });

        document
            .getElementById("close-channels-btn")
            ?.addEventListener("click", (e) => {
                e.preventDefault();
                channelsPopup.classList.remove("active");
                channelsPopup.style.display = "none";
            });

        function loadChannels() {
            const channelsListContainer = document.getElementById(
                "channels-list-container",
            );
            if (!channelsListContainer) return;

            channelsListContainer.innerHTML =
                '<p style="text-align: center; color: gray; margin-top: 2rem; font-family: sans-serif;">Chargement...</p>';

            fetch(`${baseUrl}/tuteur/chats`, {
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    Accept: "application/json",
                },
            })
                .then((r) => {
                    if (!r.ok)
                        throw new Error(
                            "Réponse serveur incorrecte status " + r.status,
                        );
                    return r.json();
                })
                .then((chats) => {
                    channelsListContainer.innerHTML = "";

                    if (!chats || chats.length === 0) {
                        channelsListContainer.innerHTML =
                            '<p style="text-align: center; color: gray; margin-top: 2rem; font-family: sans-serif;">Aucune conversation trouvée.</p>';
                        return;
                    }

                    chats.forEach((c) => {
                        const student = c.user;
                        if (!student) return;

                        const item = document.createElement("div");
                        item.style.cssText =
                            "display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; border-bottom: 1px solid #f0f0f0; cursor: pointer; transition: background 0.2s;";
                        item.innerHTML = `
                            <div style="font-size: 1.5rem;">👤</div>
                            <div style="flex: 1; font-family: sans-serif;">
                                <p style="margin: 0; font-weight: 600; font-size: 0.9rem; color: #333;">${student.firstname} ${student.name}</p>
                                <p style="margin: 0; font-size: 0.75rem; color: gray; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    ${c.latest_message ? c.latest_message.contenu : "Pas de message"}
                                </p>
                            </div>
                        `;

                        item.addEventListener("click", () => {
                            channelsPopup.classList.remove("active");
                            channelsPopup.style.display = "none";
                            openChatFromNotif(
                                c.id,
                                `${student.firstname} ${student.name}`,
                            );
                        });
                        channelsListContainer.appendChild(item);
                    });
                })
                .catch((err) => {
                    console.error("Erreur lors du fetch des canaux :", err);
                    channelsListContainer.innerHTML =
                        '<p style="text-align: center; color: red; margin-top: 2rem; font-family: sans-serif;">Erreur de chargement.</p>';
                });
        }

        // Écoute tous ses chats dès le chargement, peu importe la page
        fetch(`${baseUrl}/tuteur/chats`, {
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                Accept: "application/json",
            },
        })
            .then((r) => r.json())
            .then((chats) => {
                chats.forEach((c) => listenToChannel(c.id));
            });
    }

    // Ouverture automatique après redirection depuis une notif
    const urlParams = new URLSearchParams(window.location.search);
    const openChatParam = urlParams.get("open_chat");
    const storedChatId = sessionStorage.getItem("open_chat_id");

    if (openChatParam || storedChatId) {
        const chatIdToOpen = openChatParam || storedChatId;
        const authorName = sessionStorage.getItem("open_chat_author") || "";

        setTimeout(() => {
            openChatFromNotif(parseInt(chatIdToOpen), authorName);
            sessionStorage.removeItem("open_chat_id");
            sessionStorage.removeItem("open_chat_author");

            if (openChatParam) {
                window.history.replaceState({}, "", window.location.pathname);
            }
        }, 300);
    }
});

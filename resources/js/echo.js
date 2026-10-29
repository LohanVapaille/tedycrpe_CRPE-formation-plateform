import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

// Connexion temps réel uniquement pour un utilisateur connecté (économise les connexions Pusher).
// La clé et l'URL viennent du serveur (window.chatConfig) : le même build fonctionne en local et en prod,
// quel que soit le sous-dossier (/tedycrpe/public en local, racine du domaine en prod).
const config = window.chatConfig;
const pusherKey = config?.pusherKey || import.meta.env.VITE_PUSHER_APP_KEY;

if (config && pusherKey) {
    window.Echo = new Echo({
        broadcaster: "pusher",
        key: pusherKey,
        cluster: config.pusherCluster || import.meta.env.VITE_PUSHER_APP_CLUSTER || "eu",
        forceTLS: true,
        authEndpoint: `${config.baseUrl}/broadcasting/auth`,
        auth: {
            headers: { "X-CSRF-TOKEN": config.csrfToken },
        },
    });
}

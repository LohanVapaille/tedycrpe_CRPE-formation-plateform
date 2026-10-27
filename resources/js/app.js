import "./bootstrap";
import "./echo";
import "./chat-notifications";

document.addEventListener("DOMContentLoaded", () => {
    const menuToggle = document.getElementById("menuToggle");
    const navWrapper = document.getElementById("navWrapper");
    const menuIcon = menuToggle.querySelector("i");

    menuToggle.addEventListener("click", () => {
        // Toggle des classes actives pour le menu et le bouton
        navWrapper.classList.toggle("active");
        menuToggle.classList.toggle("active");

        // Changement d'icône fluide (Burger <-> Croix)
        if (navWrapper.classList.contains("active")) {
            menuIcon.classList.remove("bx-menu");
            menuIcon.classList.add("bx-x");
        } else {
            menuIcon.classList.remove("bx-x");
            menuIcon.classList.add("bx-menu");
        }
    });

    // Avis de l'accueil : "Lire la suite" uniquement si le texte est réellement tronqué
    const majBoutonsAvis = () => {
        document.querySelectorAll(".avis-card").forEach((card) => {
            if (card.classList.contains("is-expanded")) return;
            const texte = card.querySelector(".avis-text");
            const bouton = card.querySelector(".avis-toggle");
            bouton.classList.toggle("is-visible", texte.scrollHeight > texte.clientHeight + 1);
        });
    };

    document.querySelectorAll(".avis-toggle").forEach((bouton) => {
        bouton.addEventListener("click", () => {
            const card = bouton.closest(".avis-card");
            const ouvert = card.classList.toggle("is-expanded");
            bouton.textContent = ouvert ? "Réduire" : "Lire la suite";
            bouton.setAttribute("aria-expanded", ouvert);
        });
    });

    majBoutonsAvis();
    window.addEventListener("resize", majBoutonsAvis);

    // Compteur de caractères des champs ayant un maxlength (formulaire de contact)
    document.querySelectorAll(".form-group .char-count").forEach((counter) => {
        const field = counter.parentElement.querySelector("[maxlength]");
        if (!field) return;

        const update = () => {
            counter.textContent = `${field.value.length}/${field.maxLength}`;
        };
        field.addEventListener("input", update);
        update();
    });
});

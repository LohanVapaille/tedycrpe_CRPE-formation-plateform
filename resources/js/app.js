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

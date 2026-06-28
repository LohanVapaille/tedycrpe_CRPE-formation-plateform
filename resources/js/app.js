import "./bootstrap";
import "./echo";

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
});

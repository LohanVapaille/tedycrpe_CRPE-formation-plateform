import "./bootstrap";
import "./echo";

document.addEventListener("DOMContentLoaded", () => {
    const menuToggle = document.getElementById("menuToggle");
    const navWrapper = document.getElementById("navWrapper");

    if (menuToggle && navWrapper) {
        menuToggle.addEventListener("click", () => {
            navWrapper.classList.toggle("active");
        });
    }
});

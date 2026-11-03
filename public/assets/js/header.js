/**
 * header.js - Lógica del header de la Municipalidad de Lamay
 */
document.addEventListener('DOMContentLoaded', function() {
    'use strict';

    /* ============================================
       MENÚ MÓVIL
       ============================================ */
    const menuToggle = document.querySelector(".menu-toggle");
    const navLinks = document.querySelector(".nav-links");

    if (menuToggle && navLinks) {
        menuToggle.addEventListener("click", function() {
            const isOpen = navLinks.classList.toggle("active");
            menuToggle.setAttribute("aria-expanded", String(isOpen));
        });
    }

    /* ============================================
       DROPDOWNS EN MÓVIL
       ============================================ */
    const dropdowns = document.querySelectorAll(".dropdown");
    dropdowns.forEach(dropdown => {
        const link = dropdown.querySelector(".nav-item");
        if (link) {
            link.addEventListener("click", function(e) {
                if (window.innerWidth <= 768) {
                    e.preventDefault();
                    dropdown.classList.toggle("active");

                    // Cerrar los demás dropdowns
                    dropdowns.forEach(otherDropdown => {
                        if (otherDropdown !== dropdown) {
                            otherDropdown.classList.remove("active");
                            const otherLink = otherDropdown.querySelector(".nav-item");
                            if (otherLink) {
                                otherLink.setAttribute("aria-expanded", "false");
                            }
                        }
                    });
                    link.setAttribute("aria-expanded", String(dropdown.classList.contains("active")));
                }
            });
        }
    });

    /* ============================================
       CERRAR MENÚ AL HACER CLIC EN UN ENLACE (MÓVIL)
       ============================================ */
    const allLinks = document.querySelectorAll(".dropdown-link");
    allLinks.forEach(link => {
        link.addEventListener("click", function() {
            if (window.innerWidth <= 768) {
                navLinks.classList.remove("active");
                if (menuToggle) {
                    menuToggle.setAttribute("aria-expanded", "false");
                }
            }
        });
    });
});
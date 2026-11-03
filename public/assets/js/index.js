/**
 * index.js - Script único para la página de inicio
 * Municipalidad de Lamay
 */
(function() {
    'use strict';

    const CACHE_KEY = 'muni_config_data';
    const API_URL = "/api/configuracion-institucional";

    let rotativeIndex = 0;
    let rotativePhrases = [];
    let intervalTimer = null;

    /* ============================================
       CONFIGURACIÓN INSTITUCIONAL (con caché)
       ============================================ */
    function obtenerConfiguracionMuni() {
        const cached = sessionStorage.getItem(CACHE_KEY);
        if (cached) {
            try { return Promise.resolve(JSON.parse(cached)); }
            catch (e) { sessionStorage.removeItem(CACHE_KEY); }
        }

        if (window.muniConfigPromise) {
            return window.muniConfigPromise;
        }

        window.muniConfigPromise = fetch(API_URL)
            .then(res => {
                if (!res.ok) throw new Error("HTTP " + res.status);
                return res.json();
            })
            .then(res => {
                if (res.status === 'success' && res.data) {
                    sessionStorage.setItem(CACHE_KEY, JSON.stringify(res.data));
                    return res.data;
                }
                throw new Error("Respuesta inválida de la API");
            })
            .catch(err => {
                window.muniConfigPromise = null;
                throw err;
            });

        return window.muniConfigPromise;
    }

    /* ============================================
       TEXTOS ROTATIVOS DEL HERO
       ============================================ */
    function iniciarTextosRotativos(frases) {
        if (!Array.isArray(frases) || frases.length === 0) return;
        rotativePhrases = frases.map(f => typeof f === 'object' ? f.texto : f);
        const subEl = document.getElementById('rotating-text');
        if (!subEl) return;

        subEl.textContent = rotativePhrases[0];
        if (intervalTimer) clearInterval(intervalTimer);

        if (rotativePhrases.length > 1) {
            intervalTimer = setInterval(() => {
                subEl.classList.add('text-fade-out');
                setTimeout(() => {
                    rotativeIndex = (rotativeIndex + 1) % rotativePhrases.length;
                    subEl.textContent = rotativePhrases[rotativeIndex];
                    subEl.classList.remove('text-fade-out');
                    subEl.classList.add('text-fade-in');
                    setTimeout(() => subEl.classList.remove('text-fade-in'), 500);
                }, 400);
            }, 3500);
        }
    }

    /* ============================================
       APLICAR DATOS AL HEADER + FOOTER
       ============================================ */
    function aplicarDatos(data) {
        if (!data) return;

        const cleanNum = n => n ? String(n).replace(/\s+/g, '') : '';

        // ---- HEADER ----
        const phoneHeader = document.getElementById('header-phone');
        if (phoneHeader && data.telefono_principal) {
            phoneHeader.textContent = data.telefono_principal;
            phoneHeader.href = 'tel:' + cleanNum(data.telefono_principal);
        }

        const emailHeader = document.getElementById('header-email');
        if (emailHeader && data.email_principal) {
            emailHeader.textContent = data.email_principal;
            emailHeader.href = 'mailto:' + data.email_principal;
        }

        const addrHeader = document.getElementById('header-address');
        if (addrHeader && data.direccion_corta) {
            addrHeader.textContent = data.direccion_corta;
        }

        // ---- LOGO ----
        if (data.logo_url) {
            document.querySelectorAll('#site-logo, #footer-logo').forEach(el => el.src = data.logo_url);
        }

        // ---- REDES SOCIALES (header + footer) ----
        if (data.facebook_url) {
            ['social-facebook', 'footer-facebook'].forEach(id => {
                const el = document.getElementById(id);
                if (el) { el.href = data.facebook_url; el.style.display = 'inline-flex'; }
            });
        }
        if (data.whatsapp_numero) {
            ['social-whatsapp', 'footer-whatsapp'].forEach(id => {
                const el = document.getElementById(id);
                if (el) { el.href = `https://wa.me/51${data.whatsapp_numero}`; el.style.display = 'inline-flex'; }
            });
        }
        if (data.tiktok_url) {
            ['social-tiktok', 'footer-tiktok'].forEach(id => {
                const el = document.getElementById(id);
                if (el) { el.href = data.tiktok_url; el.style.display = 'inline-flex'; }
            });
        }
        if (data.youtube_url) {
            ['social-youtube', 'footer-youtube'].forEach(id => {
                const el = document.getElementById(id);
                if (el) { el.href = data.youtube_url; el.style.display = 'inline-flex'; }
            });
        }

        // ---- VIDEO HERO ----
        if (data.video_hero_url) {
            const player = document.getElementById('hero-video-player');
            const source = document.getElementById('hero-video-source');
            if (player && source) {
                source.src = data.video_hero_url;
                player.load();
                player.style.display = 'block';
                player.play().catch(e => console.log('Autoplay suspendido:', e));
            }
        }

        // ---- HERO TÍTULO ----
        if (data.hero_titulo) {
            const titleEl = document.getElementById('hero-title');
            if (titleEl) titleEl.textContent = data.hero_titulo;
        }

        // ---- HERO TEXTOS ROTATIVOS ----
        if (data.hero_textos_rotativos) {
            iniciarTextosRotativos(data.hero_textos_rotativos);
        }

        // ---- HERO BOTÓN ----
        if (data.hero_boton_url) {
            const btnEl = document.getElementById('hero-action-btn');
            if (btnEl) {
                btnEl.href = data.hero_boton_url;
                btnEl.style.display = 'inline-block';
            }
        }

        // ---- EMERGENCIAS ----
        const elPnp = document.getElementById('emergencia-pnp');
        if (elPnp && data.emergencia_pnp) {
            elPnp.textContent = data.emergencia_pnp;
            elPnp.href = 'tel:' + cleanNum(data.emergencia_pnp);
        }

        const elSerenazgo = document.getElementById('emergencia-serenazgo');
        if (elSerenazgo && data.emergencia_serenazgo) {
            elSerenazgo.textContent = data.emergencia_serenazgo;
            elSerenazgo.href = 'tel:' + cleanNum(data.emergencia_serenazgo);
        }

        const elDefensa = document.getElementById('emergencia-defensa-civil');
        if (elDefensa && data.emergencia_defensa_civil) {
            elDefensa.textContent = data.emergencia_defensa_civil;
            elDefensa.href = 'tel:' + cleanNum(data.emergencia_defensa_civil);
        }

        // ---- FOOTER CONTACTO ----
        const footerAddr = document.getElementById('footer-address');
        if (footerAddr && data.direccion_corta) footerAddr.textContent = data.direccion_corta;

        const footerPhone = document.getElementById('footer-phone');
        if (footerPhone && data.telefono_principal) {
            footerPhone.textContent = data.telefono_principal;
            footerPhone.href = 'tel:' + cleanNum(data.telefono_principal);
        }

        const footerEmail = document.getElementById('footer-email');
        if (footerEmail && data.email_principal) {
            footerEmail.textContent = data.email_principal;
            footerEmail.href = 'mailto:' + data.email_principal;
        }
    }

    /* ============================================
       INICIALIZACIÓN
       ============================================ */
    document.addEventListener('DOMContentLoaded', function() {

        // ---- MENÚ MÓVIL ----
        const menuBtn = document.getElementById('menu-toggle-btn');
        const navLinks = document.getElementById('nav-links');
        if (menuBtn && navLinks) {
            menuBtn.addEventListener('click', () => navLinks.classList.toggle('active'));
        }

        // ---- NAVBAR SCROLLED ----
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('main-navbar');
            if (nav) {
                if (window.scrollY > 60) nav.classList.add('scrolled');
                else nav.classList.remove('scrolled');
            }
        });

        // ---- BOTÓN VOLVER ARRIBA ----
        const backToTop = document.getElementById('back-to-top');
        if (backToTop) {
            window.addEventListener('scroll', () => {
                if (window.scrollY > 400) backToTop.classList.add('visible');
                else backToTop.classList.remove('visible');
            });
            backToTop.addEventListener('click', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }

        // ---- CARGAR CONFIGURACIÓN ----
        obtenerConfiguracionMuni()
            .then(data => aplicarDatos(data))
            .catch(err => console.warn("Error cargando configuración:", err));
    });
})();
<?php
// 0. Evita avisos si $baseUrl aún no está definida cuando se usa abajo
$baseUrl = $baseUrl ?? '';

require_once __DIR__.'/api.php';
$institucional = muni_api_data('configuracion-institucional') ?? [];

// 2. Variables preparadas y limpias (con fallbacks)
$telefono = $institucional['telefono_principal'] ?? '957 756 538';
$email = $institucional['email_principal'] ?? 'mesadepartes@munilamay.gob.pe';
$direccionCorta = $institucional['direccion_corta'] ?? 'Plaza de Armas, Lamay';
$mapaUrl = $institucional['mapa_url'] ?? '';

$waNumero = preg_replace('/\D/', '', $institucional['whatsapp_numero'] ?? '957756538');
$telNumero = preg_replace('/\D/', '', $telefono);

$facebookUrl = $institucional['facebook_url'] ?? '';
$tiktokUrl = $institucional['tiktok_url'] ?? '';
$youtubeUrl = $institucional['youtube_url'] ?? '';

$videoHeroUrl = $institucional['video_hero_url'] ?? '';
$logoUrl = $institucional['logo_url'] ?? '';
$heroTitulo = $institucional['hero_titulo'] ?? 'LAMAY';
$heroBotonUrl = $institucional['hero_boton_url'] ?? ($baseUrl.'/historia/');
$textosRotativos = $institucional['hero_textos_rotativos'] ?? [
    ['texto' => 'El encanto del valle'],
];
?>

<a class="skip-link" href="#contenido">Saltar al contenido</a>

<header class="muni-header">
  <div class="top-bar">
    <div class="top-bar-content">
      <div class="contact-info">
        <span class="contact-item">
          <i class="fas fa-phone" aria-hidden="true"></i>
          <a id="header-phone" href="tel:+51<?= htmlspecialchars($telNumero) ?>"><?= htmlspecialchars($telefono) ?></a>
        </span>
        <span class="contact-item contact-item--extra">
          <i class="fas fa-envelope" aria-hidden="true"></i>
          <a id="header-email" href="mailto:<?= htmlspecialchars($email) ?>"><?= htmlspecialchars($email) ?></a>
        </span>
        <span class="contact-item contact-item--extra">
          <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
          <?php if (! empty($mapaUrl)) { ?>
            <a id="header-address" href="<?= htmlspecialchars($mapaUrl) ?>" target="_blank" rel="noopener noreferrer"><?= htmlspecialchars($direccionCorta) ?><span class="sr-only"> (se abre en una pestaña nueva)</span></a>
          <?php } else { ?>
            <span id="header-address"><?= htmlspecialchars($direccionCorta) ?></span>
          <?php } ?>
        </span>
      </div>

      <div class="social-icons" role="group" aria-label="Redes sociales">
        <?php if (! empty($facebookUrl)) { ?>
          <a id="social-facebook" href="<?= htmlspecialchars($facebookUrl) ?>" target="_blank" rel="noopener noreferrer" aria-label="Facebook (se abre en una pestaña nueva)">
            <i class="fab fa-facebook-f" aria-hidden="true"></i>
          </a>
        <?php } ?>

        <?php if (! empty($waNumero)) { ?>
          <a id="social-whatsapp" href="https://wa.me/51<?= htmlspecialchars($waNumero) ?>" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp (se abre en una pestaña nueva)">
            <i class="fab fa-whatsapp" aria-hidden="true"></i>
          </a>
        <?php } ?>

        <?php if (! empty($tiktokUrl)) { ?>
          <a id="social-tiktok" href="<?= htmlspecialchars($tiktokUrl) ?>" target="_blank" rel="noopener noreferrer" aria-label="TikTok (se abre en una pestaña nueva)">
            <i class="fab fa-tiktok" aria-hidden="true"></i>
          </a>
        <?php } ?>

        <?php if (! empty($youtubeUrl)) { ?>
          <a id="social-youtube" href="<?= htmlspecialchars($youtubeUrl) ?>" target="_blank" rel="noopener noreferrer" aria-label="YouTube (se abre en una pestaña nueva)">
            <i class="fab fa-youtube" aria-hidden="true"></i>
          </a>
        <?php } ?>
      </div>
    </div>
  </div>

  <section class="hero-section" aria-labelledby="hero-title">
    <div class="video-background" aria-hidden="true">
      <video id="hero-video-player" autoplay loop muted playsinline>
        <source id="hero-video-source" src="<?= htmlspecialchars($videoHeroUrl) ?>" type="video/mp4">
      </video>
      <div class="video-overlay-header"></div>
    </div>

    <nav class="nav-bar" id="main-navbar" aria-label="Navegación principal">
      <div class="nav-content">
        <a class="home-logo-link" href="<?= $baseUrl ?>/" aria-label="Inicio - Municipalidad Distrital de Lamay">
          <?php if (! empty($logoUrl)) { ?>
            <img src="<?= htmlspecialchars($logoUrl) ?>" alt="" class="home-logo-img">
          <?php } else { ?>
            <span class="home-logo-mark" aria-hidden="true"><i class="fas fa-mountain"></i></span>
          <?php } ?>
          <span class="home-logo-copy"><strong>MUNICIPALIDAD DISTRITAL</strong><small>DE LAMAY · CUSCO</small></span>
        </a>

        <button class="menu-toggle" id="menu-toggle-btn" type="button" aria-label="Abrir menú" aria-expanded="false" aria-controls="nav-links">
          <i class="fas fa-bars" aria-hidden="true"></i>
          <span class="menu-toggle-text">Menú</span>
        </button>

        <div class="nav-center">
          <div class="nav-links" id="nav-links">

            <div class="dropdown">
              <button type="button" class="nav-item" aria-expanded="false" aria-controls="sub-municipalidad">
                <i class="fas fa-building" aria-hidden="true"></i><span>Municipalidad</span>
                <i class="fas fa-chevron-down nav-caret" aria-hidden="true"></i>
              </button>
              <div class="dropdown-content" id="sub-municipalidad">
                <div class="dropdown-section">
                  <a href="<?= $baseUrl ?>/alcalde/" class="dropdown-link">Alcalde y regidores</a>
                  <a href="<?= $baseUrl ?>/mision-vision/" class="dropdown-link">Misión y visión</a>
                  <a href="<?= $baseUrl ?>/historia/" class="dropdown-link">Historia</a>
                  <a href="<?= $baseUrl ?>/organigrama/" class="dropdown-link">Organigrama</a>
                </div>
              </div>
            </div>

            <div class="dropdown">
              <button type="button" class="nav-item" aria-expanded="false" aria-controls="sub-servicios">
                <i class="fas fa-hands-helping" aria-hidden="true"></i><span>Servicios</span>
                <i class="fas fa-chevron-down nav-caret" aria-hidden="true"></i>
              </button>
              <div class="dropdown-content" id="sub-servicios">
                <div class="dropdown-section">
                  <a href="<?= $baseUrl ?>/demuna/" class="dropdown-link">DEMUNA</a>
                  <a href="<?= $baseUrl ?>/codisec/" class="dropdown-link">CODISEC</a>
                  <a href="<?= $baseUrl ?>/desarrollo-urbano-y-rural/" class="dropdown-link">Desarrollo urbano y rural</a>
                </div>
              </div>
            </div>

            <div class="dropdown">
              <button type="button" class="nav-item" aria-expanded="false" aria-controls="sub-transparencia">
                <i class="fas fa-file-alt" aria-hidden="true"></i><span>Transparencia</span>
                <i class="fas fa-chevron-down nav-caret" aria-hidden="true"></i>
              </button>
              <div class="dropdown-content" id="sub-transparencia">
                <div class="dropdown-section">
                  <div class="dropdown-section-title">Documentos normativos</div>
                  <a href="<?= $baseUrl ?>/acuerdos%20de%20concejo/" class="dropdown-link">Acuerdos de concejo</a>
                  <a href="<?= $baseUrl ?>/resoluciones-de-alcaldia/" class="dropdown-link">Resoluciones de alcaldía</a>
                  <a href="<?= $baseUrl ?>/decreto-de-alcaldia/" class="dropdown-link">Decretos de alcaldía</a>
                  <a href="<?= $baseUrl ?>/ordenanzas-municipales/" class="dropdown-link">Ordenanzas municipales</a>
                  <a href="<?= $baseUrl ?>/resoluciones-de-gerencia/" class="dropdown-link">Resoluciones de gerencia</a>
                  <a href="<?= $baseUrl ?>/directivas/" class="dropdown-link">Directivas municipales</a>
                </div>
                <div class="dropdown-section">
                  <div class="dropdown-section-title">Gestión y convocatorias</div>
                  <a href="<?= $baseUrl ?>/instrumentos_gestion/" class="dropdown-link">Instrumentos de gestión</a>
                  <a href="<?= $baseUrl ?>/convocatorias-cas/" class="dropdown-link">Convocatorias CAS</a>
                </div>
                <div class="dropdown-section">
                  <div class="dropdown-section-title">Otros documentos</div>
                  <a href="<?= $baseUrl ?>/otros-documentos-de-interes/" class="dropdown-link">Documentos de interés</a>
                  <a href="<?= $baseUrl ?>/proceso%20de%20seleccion/" class="dropdown-link">Procesos de selección</a>
                  <a href="<?= $baseUrl ?>/Interaccion%20con%20el%20mercado/" class="dropdown-link">Interacción con el mercado</a>
                </div>
              </div>
            </div>

            <!-- Visible solo en móvil dentro del panel -->
            <a class="portal-transparencia portal-transparencia--mobile" href="<?= $baseUrl ?>/acuerdos%20de%20concejo/">Portal de transparencia <span aria-hidden="true">→</span></a>
          </div>
        </div>

        <!-- Visible solo en escritorio -->
        <a class="portal-transparencia portal-transparencia--desktop" href="<?= $baseUrl ?>/acuerdos%20de%20concejo/">Portal de transparencia <span aria-hidden="true">→</span></a>
      </div>
    </nav>

    <div class="hero-content">
      <div class="hero-text">
        <p class="hero-kicker">Bienvenidos</p>
        <h1 id="hero-title" class="hero-title"><?= htmlspecialchars($heroTitulo) ?></h1>
        <h2 id="rotating-text" class="hero-subtitle" aria-live="off"><?= htmlspecialchars($textosRotativos[0]['texto'] ?? 'El encanto del valle') ?></h2>
        <a id="hero-action-btn" href="<?= htmlspecialchars($heroBotonUrl) ?>" <?= str_starts_with($heroBotonUrl, 'http') ? 'target="_blank"' : '' ?> rel="noopener noreferrer" class="hero-button">
          Conoce más <i class="fas fa-arrow-right" aria-hidden="true"></i>
        </a>
      </div>
    </div>
    <a class="hero-scroll" href="#servicios" aria-label="Ver servicios municipales"><i class="fas fa-chevron-down" aria-hidden="true"></i></a>
  </section>
</header>

<!-- Menú: abrir/cerrar, submenús, Escape y clic fuera -->
<script>
(function () {
  var nav     = document.getElementById('main-navbar');
  var toggle  = document.getElementById('menu-toggle-btn');
  var panel   = document.getElementById('nav-links');
  if (!nav || !toggle || !panel) return;

  var mq       = window.matchMedia('(max-width: 991px)');
  var triggers = Array.prototype.slice.call(panel.querySelectorAll('.dropdown > .nav-item'));

  function updateNavBottom() {
    document.documentElement.style.setProperty('--nav-bottom', Math.max(0, Math.round(nav.getBoundingClientRect().bottom)) + 'px');
  }
  updateNavBottom();

  function setSub(btn, open) {
    btn.setAttribute('aria-expanded', open ? 'true' : 'false');
    btn.parentElement.classList.toggle('is-open', open);
  }
  function closeSubs(except) {
    triggers.forEach(function (t) { if (t !== except) setSub(t, false); });
  }
  function setMenu(open) {
    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    toggle.setAttribute('aria-label', open ? 'Cerrar menú' : 'Abrir menú');
    toggle.querySelector('i').className = open ? 'fas fa-times' : 'fas fa-bars';
    nav.classList.toggle('menu-open', open);
    document.body.classList.toggle('menu-lock', open && mq.matches);
    if (open) updateNavBottom(); else closeSubs();
  }

  toggle.addEventListener('click', function () {
    setMenu(toggle.getAttribute('aria-expanded') !== 'true');
  });

  triggers.forEach(function (btn) {
    btn.addEventListener('click', function () {
      var open = btn.getAttribute('aria-expanded') !== 'true';
      closeSubs(btn);
      setSub(btn, open);
    });
  });

  document.addEventListener('keydown', function (e) {
    if (e.key !== 'Escape') return;
    var active = document.activeElement;
    var openSub = triggers.filter(function (t) { return t.getAttribute('aria-expanded') === 'true'; })[0];
    closeSubs();
    if (mq.matches) setMenu(false);
    if (openSub && nav.contains(active) && !mq.matches) openSub.focus();
    else if (mq.matches && nav.contains(active)) toggle.focus();
  });

  document.addEventListener('click', function (e) {
    if (nav.contains(e.target)) return;
    closeSubs();
    if (mq.matches) setMenu(false);
  });

  // Al elegir un enlace en móvil, cerrar el menú
  panel.addEventListener('click', function (e) {
    var link = e.target.closest('a');
    if (link && mq.matches) setMenu(false);
  });

  ['resize', 'scroll', 'orientationchange'].forEach(function (ev) {
    window.addEventListener(ev, function () {
      if (nav.classList.contains('menu-open')) updateNavBottom();
    }, { passive: true });
  });

  var onChange = function () { setMenu(false); };
  if (mq.addEventListener) mq.addEventListener('change', onChange);
  else mq.addListener(onChange);
})();
</script>

<!-- Rotación automática de los textos del hero (respeta "reducir movimiento") -->
<?php if (! empty($textosRotativos) && count($textosRotativos) > 1) { ?>
<script>
  (function () {
    var rotativos = <?= json_encode(array_values(array_column($textosRotativos, 'texto')), JSON_UNESCAPED_UNICODE) ?>;
    var el = document.getElementById('rotating-text');
    if (!el || rotativos.length <= 1) return;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    var index = 0;
    setInterval(function () {
      index = (index + 1) % rotativos.length;
      el.style.opacity = '0';
      setTimeout(function () {
        el.textContent = rotativos[index];
        el.style.opacity = '1';
      }, 300);
    }, 3500);
  })();
</script>
<?php } ?>

<style>
/* =========================================================
   Menú accesible y responsive - Municipalidad Distrital de Lamay
   v2: panel fijo (no se recorta), resets defensivos, tablet y hero
   ========================================================= */
:root {
  --menu-bg: #12324a;
  --menu-bg-sub: #0c2336;
  --menu-text: #ffffff;
  --menu-line: rgba(255, 255, 255, .16);
  --menu-accent: #ffd166;
  --menu-accent-text: #1a1a1a;
  --tap: 48px;
  --nav-bottom: 110px; /* lo actualiza el JS con la posición real de la barra */
}

html { -webkit-text-size-adjust: 100%; }
.muni-header { overflow-x: clip; }
.muni-header img { max-width: 100%; height: auto; }

.sr-only {
  position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px;
  overflow: hidden; clip: rect(0, 0, 0, 0); white-space: nowrap; border: 0;
}
.skip-link {
  position: absolute; left: 8px; top: -100px; z-index: 10000;
  background: var(--menu-accent); color: var(--menu-accent-text);
  padding: 12px 16px; border-radius: 6px; font-weight: 700;
}
.skip-link:focus { top: 8px; }

.muni-header a:focus-visible,
.muni-header button:focus-visible {
  outline: 3px solid var(--menu-accent); outline-offset: 2px; border-radius: 4px;
}

.muni-header .nav-item,
.muni-header .menu-toggle {
  background: none; border: 0; font: inherit; color: inherit; cursor: pointer; text-align: left;
}
.muni-header .nav-item { display: flex; align-items: center; gap: 10px; }
.muni-header .nav-caret { font-size: .75em; margin-left: auto; transition: transform .2s ease; }
.muni-header .dropdown.is-open .nav-caret { transform: rotate(180deg); }
.muni-header .home-logo-img { max-height: 44px; width: auto; margin-right: 8px; vertical-align: middle; }

/* Video siempre cubre el hero */
.muni-header .video-background video { width: 100%; height: 100%; object-fit: cover; }

/* ---------- Escritorio (≥ 992px) ---------- */
@media (min-width: 992px) {
  .muni-header .menu-toggle,
  .muni-header .portal-transparencia--mobile { display: none; }

  .muni-header .dropdown { position: relative; }
  .muni-header .dropdown-content { display: none; }
  .muni-header .dropdown:hover > .dropdown-content,
  .muni-header .dropdown:focus-within > .dropdown-content,
  .muni-header .dropdown.is-open > .dropdown-content { display: block; }
  .muni-header .nav-item:hover,
  .muni-header .dropdown.is-open > .nav-item { color: var(--menu-accent); }
}

/* Laptops pequeñas / tablets horizontales: que todo quepa en una fila */
@media (min-width: 992px) and (max-width: 1199px) {
  .muni-header .nav-content { gap: 12px; }
  .muni-header .home-logo-copy small { display: none; }
  .muni-header .nav-links .nav-item { font-size: .92rem; padding-left: 10px; padding-right: 10px; }
  .muni-header .portal-transparencia--desktop { font-size: .9rem; white-space: nowrap; }
}

/* ---------- Móvil y tablet (≤ 991px) ---------- */
@media (max-width: 991px) {
  body.menu-lock { overflow: hidden; }

  .muni-header .top-bar-content {
    display: flex; justify-content: space-between; align-items: center;
    gap: 8px; padding: 4px 12px;
  }
  .muni-header .contact-item--extra { display: none; }
  .muni-header .contact-item a { display: inline-flex; align-items: center; min-height: var(--tap); font-size: 1rem; }
  .muni-header .social-icons { display: flex; gap: 4px; }
  .muni-header .social-icons a {
    display: inline-flex; align-items: center; justify-content: center;
    width: var(--tap); height: var(--tap); font-size: 1.15rem;
  }

  .muni-header .nav-bar {
    position: relative; z-index: 50;
    background: var(--menu-bg); color: var(--menu-text);
  }
  .muni-header .nav-content {
    display: flex; align-items: center; justify-content: space-between;
    gap: 12px; padding: 6px 12px; min-height: 64px;
  }
  .muni-header .home-logo-link { display: flex; align-items: center; min-width: 0; color: var(--menu-text); }
  .muni-header .home-logo-copy { min-width: 0; }
  .muni-header .home-logo-copy strong { display: block; font-size: .8rem; line-height: 1.15; }
  .muni-header .home-logo-copy small { font-size: .7rem; opacity: .9; }

  .muni-header .menu-toggle {
    display: inline-flex; align-items: center; gap: 8px;
    min-height: var(--tap); padding: 0 14px;
    color: var(--menu-text); border: 2px solid var(--menu-text); border-radius: 10px;
    font-weight: 700; font-size: 1rem; flex-shrink: 0;
  }
  .muni-header .menu-toggle[aria-expanded="true"] {
    background: var(--menu-accent); color: var(--menu-accent-text); border-color: var(--menu-accent);
  }

  /* Panel FIJO: no lo recorta ningún contenedor con overflow del hero */
  .muni-header .nav-center {
    display: none;
    position: fixed; left: 0; right: 0; top: var(--nav-bottom); z-index: 60;
    width: auto; height: auto; transform: none; opacity: 1; visibility: visible;
    margin: 0; padding: 0;
    background: var(--menu-bg);
    border-top: 1px solid var(--menu-line);
    box-shadow: 0 12px 24px rgba(0, 0, 0, .35);
    max-height: calc(100vh - var(--nav-bottom));
    max-height: calc(100dvh - var(--nav-bottom));
    overflow-y: auto; overscroll-behavior: contain;
    -webkit-overflow-scrolling: touch;
  }
  .muni-header .nav-bar.menu-open .nav-center { display: block; }

  /* Resets por si tu CSS viejo ocultaba/movía .nav-links */
  .muni-header .nav-links {
    display: flex; flex-direction: column; align-items: stretch;
    position: static; transform: none; opacity: 1; visibility: visible;
    width: auto; height: auto; margin: 0; padding: 4px 0 16px;
    background: transparent; box-shadow: none;
  }

  .muni-header .nav-links .nav-item {
    width: 100%; min-height: var(--tap); padding: 10px 16px;
    font-size: 1.05rem; font-weight: 600; color: var(--menu-text);
    border-bottom: 1px solid var(--menu-line);
  }
  .muni-header .nav-links .nav-item:hover { color: var(--menu-accent); }
  .muni-header .dropdown { position: static; width: 100%; }
  .muni-header .dropdown.is-open > .nav-item { background: var(--menu-bg-sub); color: var(--menu-accent); }

  .muni-header .dropdown-content {
    display: none; position: static; transform: none; opacity: 1; visibility: visible;
    width: auto; min-width: 0; box-shadow: none; border: 0; margin: 0; padding: 4px 0;
    background: var(--menu-bg-sub);
  }
  .muni-header .dropdown.is-open > .dropdown-content { display: block; }

  .muni-header .dropdown-section-title {
    padding: 12px 16px 4px 24px; font-size: .85rem; font-weight: 700;
    color: var(--menu-accent); letter-spacing: .02em;
  }
  .muni-header .dropdown-link {
    display: flex; align-items: center; min-height: var(--tap);
    padding: 10px 16px 10px 24px; font-size: 1rem; line-height: 1.3;
    color: var(--menu-text); border-bottom: 1px solid var(--menu-line);
  }
  .muni-header .dropdown-link:hover,
  .muni-header .dropdown-link:active { background: rgba(255, 255, 255, .08); color: var(--menu-accent); }

  .muni-header .portal-transparencia--desktop { display: none; }
  .muni-header .portal-transparencia--mobile {
    display: flex; align-items: center; justify-content: center; gap: 8px;
    min-height: var(--tap); margin: 14px 16px 4px;
    background: var(--menu-accent); color: var(--menu-accent-text);
    font-weight: 700; border-radius: 10px;
  }

  /* Hero */
  .muni-header .hero-section { min-height: 70vh; min-height: 70svh; }
  .muni-header .hero-content { padding: 32px 16px; }
  .muni-header .hero-title { font-size: clamp(2.2rem, 11vw, 4rem); line-height: 1.05; overflow-wrap: anywhere; }
  .muni-header .hero-subtitle { font-size: clamp(1.1rem, 5vw, 1.6rem); }
  .muni-header .hero-button {
    display: inline-flex; align-items: center; justify-content: center; gap: 8px;
    min-height: var(--tap); padding: 0 24px;
  }
}

/* Teléfonos muy angostos */
@media (max-width: 380px) {
  .muni-header .home-logo-copy small { display: none; }
  .muni-header .menu-toggle-text {
    position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0 0 0 0);
  }
  .muni-header .menu-toggle { padding: 0 12px; }
}

/* Teléfono horizontal: poco alto, el panel ya se desplaza */
@media (max-width: 991px) and (orientation: landscape) and (max-height: 500px) {
  .muni-header .hero-section { min-height: 100svh; }
}

@media (prefers-reduced-motion: reduce) {
  .muni-header *, .muni-header *::before, .muni-header *::after {
    transition: none !important; animation: none !important;
  }
}
</style>

<?php
require dirname(__DIR__).'/includes/bootstrap.php';
$pageTitle = 'Instrumentos de gestión';
$pageDescription = 'Consulta los documentos normativos de planificación, organización y gestión de la Municipalidad Distrital de Lamay.';
$pageStyles = ['instrumentos-gestion.css'];
require dirname(__DIR__).'/includes/header.php';
?>
<main class="page-main instrumentos-page">
  <div class="container">
    <nav class="breadcrumbs" aria-label="Ruta de navegación">
      <a href="<?= $baseUrl ?>/">Inicio</a>
      <span aria-hidden="true">/</span>
      <span>Transparencia</span>
      <span aria-hidden="true">/</span>
      <span aria-current="page">Instrumentos de gestión</span>
    </nav>

    <div class="gestion-wrapper">
      <header class="gestion-header">
        <span class="header-badge"><i class="fas fa-landmark" aria-hidden="true"></i> Gestión Pública Transparente</span>
        <h1><i class="fas fa-file-signature" aria-hidden="true"></i> Instrumentos de Gestión</h1>
        <p>Documentos normativos que regulan y establecen la estructura organizativa, personal y funciones de la Municipalidad Distrital de Lamay.</p>
        <div class="header-divider" aria-hidden="true"></div>
      </header>

      <div class="search-control-bar">
        <div class="search-input-box">
          <i class="fas fa-search" aria-hidden="true"></i>
          <label class="gestion-sr-only" for="search-instrumentos">Buscar instrumentos de gestión</label>
          <input
            type="search"
            id="search-instrumentos"
            placeholder="Buscar por tipo, nombre o año (ej. ROF, 2026, Personal)..."
            autocomplete="off"
          >
        </div>
      </div>

      <section class="documentos-grid" id="instrumentos-container" aria-live="polite" aria-busy="true">
        <div class="estado-box">
          <i class="fas fa-circle-notch spinner" aria-hidden="true"></i>
          <p>Conectando con el repositorio oficial de instrumentos...</p>
        </div>
      </section>
    </div>
  </div>
</main>
<script src="<?= $baseUrl ?>/assets/js/instrumentos-gestion.js?v=<?= filemtime(dirname(__DIR__).'/assets/js/instrumentos-gestion.js') ?>" defer></script>
<?php require dirname(__DIR__).'/includes/footer.php'; ?>

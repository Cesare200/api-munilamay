<?php
require dirname(__DIR__).'/includes/bootstrap.php';
$pageTitle = 'Resoluciones de gerencia';
$pageDescription = 'Consulta y descarga las resoluciones de gerencia municipal de Lamay.';
$pageStyles = ['resoluciones-gerencia.css'];
require dirname(__DIR__).'/includes/header.php';
?>
<main class="page-main gerencia-page">
  <div class="container">
    <nav class="breadcrumbs" aria-label="Ruta de navegación">
      <a href="<?= $baseUrl ?>/">Inicio</a>
      <span aria-hidden="true">/</span>
      <span>Transparencia</span>
      <span aria-hidden="true">/</span>
      <span aria-current="page">Resoluciones de gerencia</span>
    </nav>

    <section class="gerencia-container" aria-labelledby="gerencia-title">
      <header class="gerencia-header">
        <h1 id="gerencia-title"><i class="fas fa-file-signature" aria-hidden="true"></i> Resoluciones de Gerencia Municipal</h1>
        <p class="gerencia-subtitle">Disposiciones administrativas y resolutivas de la Municipalidad Distrital de Lamay</p>
        <div class="gerencia-search">
          <i class="fas fa-search" aria-hidden="true"></i>
          <label class="sr-only" for="search-resoluciones-gerencia">Buscar resoluciones</label>
          <input type="search" id="search-resoluciones-gerencia" placeholder="Buscar por número o año (ej. 017 o 2026)..." autocomplete="off">
        </div>
      </header>

      <div class="gerencia-filters">
        <div class="gerencia-year-filters" id="filtros-anios-gerencia" aria-label="Filtrar resoluciones por año"></div>
        <div class="gerencia-sort">
          <label for="sort-gerencia">Ordenar por:</label>
          <select id="sort-gerencia">
            <option value="date-desc">Fecha (reciente primero)</option>
            <option value="date-asc">Fecha (antiguo primero)</option>
            <option value="number-desc">Número (mayor primero)</option>
            <option value="number-asc">Número (menor primero)</option>
          </select>
        </div>
      </div>

      <div class="gerencia-grid" id="resoluciones-gerencia-grid" aria-live="polite" aria-busy="true">
        <div class="gerencia-loading">
          <span class="loading-spinner" aria-hidden="true"></span>
          <p>Cargando resoluciones de gerencia...</p>
        </div>
      </div>
    </section>
  </div>
</main>

<div class="gerencia-modal" id="resolucion-gerencia-modal" aria-hidden="true">
  <section class="gerencia-modal-content" role="dialog" aria-modal="true" aria-labelledby="modal-gerencia-title" tabindex="-1">
    <button class="gerencia-modal-close" type="button" aria-label="Cerrar vista previa">
      <i class="fas fa-times" aria-hidden="true"></i>
    </button>
    <h2 id="modal-gerencia-title">Resolución de Gerencia Municipal</h2>
    <div class="gerencia-modal-body"></div>
    <footer class="gerencia-modal-footer">
      <a class="download-btn" id="modal-gerencia-download" href="#" target="_blank" rel="noopener noreferrer">
        <i class="fas fa-download" aria-hidden="true"></i> Abrir documento completo
      </a>
    </footer>
  </section>
</div>

<script src="<?= $baseUrl ?>/assets/js/resoluciones-gerencia.js?v=<?= filemtime(dirname(__DIR__).'/assets/js/resoluciones-gerencia.js') ?>" defer></script>
<?php require dirname(__DIR__).'/includes/footer.php'; ?>

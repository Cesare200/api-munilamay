<?php
require dirname(__DIR__).'/includes/bootstrap.php';
$pageTitle = 'Convocatorias CAS';
$pageDescription = 'Consulta y descarga las bases, cronogramas, resultados y comunicados de las convocatorias CAS de Lamay.';
$pageStyles = ['convocatorias-cas.css'];
require dirname(__DIR__).'/includes/header.php';
?>
<main class="page-main cas-page">
  <div class="container">
    <nav class="breadcrumbs" aria-label="Ruta de navegación">
      <a href="<?= $baseUrl ?>/">Inicio</a>
      <span aria-hidden="true">/</span>
      <span>Transparencia</span>
      <span aria-hidden="true">/</span>
      <span aria-current="page">Convocatorias CAS</span>
    </nav>

    <section class="cas-container" aria-labelledby="cas-title">
      <header class="cas-header">
        <h1 id="cas-title">
          <i class="fas fa-file-contract" aria-hidden="true"></i>
          Contratación Administrativa de Servicios (CAS)
        </h1>
        <p class="cas-subtitle">
          Procesos de Contratación Administrativa de Servicios de la Municipalidad Distrital de Lamay
        </p>
        <div class="cas-search">
          <i class="fas fa-search" aria-hidden="true"></i>
          <label class="cas-sr-only" for="search-cas">Buscar convocatorias CAS</label>
          <input type="search" id="search-cas" placeholder="Buscar por proceso, título o tipo..." autocomplete="off">
        </div>
      </header>

      <div class="cas-filters">
        <div class="cas-filter-groups">
          <div class="cas-filter-group">
            <span class="cas-filter-label">Año</span>
            <div id="filtros-anios" class="cas-filter-row" aria-label="Filtrar convocatorias por año"></div>
          </div>
          <div class="cas-filter-group">
            <span class="cas-filter-label">Proceso CAS</span>
            <div id="filtros-cas" class="cas-filter-row" aria-label="Filtrar por número de proceso CAS"></div>
          </div>
        </div>
        <div class="cas-sort">
          <label for="sort-by">Ordenar por:</label>
          <select id="sort-by">
            <option value="date-desc">Fecha (reciente primero)</option>
            <option value="date-asc">Fecha (antiguo primero)</option>
          </select>
        </div>
      </div>

      <div class="cas-grid" id="cas-grid" aria-live="polite" aria-busy="true">
        <div class="cas-loading">
          <span class="cas-loading-spinner" aria-hidden="true"></span>
          <p>Cargando convocatorias CAS...</p>
        </div>
      </div>
    </section>
  </div>
</main>

<div class="cas-modal" id="cas-modal" aria-hidden="true">
  <section class="cas-modal-content" role="dialog" aria-modal="true" aria-labelledby="modal-title" tabindex="-1">
    <button class="cas-modal-close" type="button" aria-label="Cerrar vista previa">
      <i class="fas fa-times" aria-hidden="true"></i>
    </button>
    <h2 id="modal-title">Documento de convocatoria CAS</h2>
    <div class="cas-modal-body"></div>
    <footer class="cas-modal-footer">
      <a href="#" class="cas-download-btn" id="modal-download-btn" target="_blank" rel="noopener noreferrer">
        <i class="fas fa-download" aria-hidden="true"></i> Descargar documento completo
      </a>
    </footer>
  </section>
</div>

<script src="<?= $baseUrl ?>/assets/js/convocatorias-cas.js?v=<?= filemtime(dirname(__DIR__).'/assets/js/convocatorias-cas.js') ?>" defer></script>
<?php require dirname(__DIR__).'/includes/footer.php'; ?>

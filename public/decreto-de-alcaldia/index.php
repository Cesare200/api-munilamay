<?php
require dirname(__DIR__).'/includes/bootstrap.php';
$pageTitle = 'Decretos de alcaldía';
$pageDescription = 'Consulta y descarga los decretos oficiales de la Municipalidad Distrital de Lamay.';
$pageStyles = ['decretos-alcaldia.css'];
require dirname(__DIR__).'/includes/header.php';
?>
<main class="page-main decretos-page">
  <div class="container">
    <nav class="breadcrumbs" aria-label="Ruta de navegación">
      <a href="<?= $baseUrl ?>/">Inicio</a>
      <span aria-hidden="true">/</span>
      <span>Transparencia</span>
      <span aria-hidden="true">/</span>
      <span aria-current="page">Decretos de alcaldía</span>
    </nav>

    <section class="decretos-container" aria-labelledby="decretos-title">
      <header class="decretos-header">
        <h1 id="decretos-title"><i class="fas fa-file-contract" aria-hidden="true"></i> Decretos de Alcaldía</h1>
        <p class="decretos-subtitle">Normas y decretos oficiales de la Municipalidad Distrital de Lamay</p>
        <div class="decretos-search">
          <i class="fas fa-search" aria-hidden="true"></i>
          <label class="sr-only" for="search-decretos">Buscar decretos</label>
          <input type="search" id="search-decretos" placeholder="Buscar por número o año..." autocomplete="off">
        </div>
      </header>

      <div class="decretos-filters" id="filters-container">
        <div class="decretos-year-filters" id="year-filters" aria-label="Filtrar decretos por año"></div>
        <div class="decretos-sort">
          <label for="sort-by-decretos">Ordenar por:</label>
          <select id="sort-by-decretos">
            <option value="date-desc">Fecha (reciente primero)</option>
            <option value="date-asc">Fecha (antiguo primero)</option>
            <option value="number-desc">Número (mayor primero)</option>
            <option value="number-asc">Número (menor primero)</option>
          </select>
        </div>
      </div>

      <div class="decretos-grid" id="decretos-grid" aria-live="polite" aria-busy="true">
        <div class="decretos-loading">
          <span class="loading-spinner" aria-hidden="true"></span>
          <p>Cargando decretos municipales...</p>
        </div>
      </div>
      <nav class="decretos-pagination" id="pagination-container" aria-label="Paginación de decretos"></nav>
    </section>
  </div>
</main>

<div class="decreto-modal" id="decreto-modal" aria-hidden="true">
  <section class="decreto-modal-content" role="dialog" aria-modal="true" aria-labelledby="modal-title-decreto" tabindex="-1">
    <button class="decreto-modal-close" type="button" aria-label="Cerrar vista previa">
      <i class="fas fa-times" aria-hidden="true"></i>
    </button>
    <h2 id="modal-title-decreto">Decreto de Alcaldía</h2>
    <div class="decreto-modal-body"></div>
    <footer class="decreto-modal-footer">
      <a class="download-btn" id="modal-download-link" href="#" target="_blank" rel="noopener noreferrer">
        <i class="fas fa-download" aria-hidden="true"></i> Abrir documento completo
      </a>
    </footer>
  </section>
</div>

<script src="<?= $baseUrl ?>/assets/js/decretos-alcaldia.js?v=<?= filemtime(dirname(__DIR__).'/assets/js/decretos-alcaldia.js') ?>" defer></script>
<?php require dirname(__DIR__).'/includes/footer.php'; ?>

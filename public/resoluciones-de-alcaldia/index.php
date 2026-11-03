<?php
require dirname(__DIR__).'/includes/bootstrap.php';
$pageTitle = 'Resoluciones de alcaldía';
$pageDescription = 'Consulta y descarga las resoluciones oficiales emitidas por la Alcaldía de Lamay.';
$pageStyles = ['resoluciones-alcaldia.css'];
require dirname(__DIR__).'/includes/header.php';
?>
<main class="page-main resoluciones-page">
  <div class="container">
    <nav class="breadcrumbs" aria-label="Ruta de navegación">
      <a href="<?= $baseUrl ?>/">Inicio</a>
      <span aria-hidden="true">/</span>
      <span>Transparencia</span>
      <span aria-hidden="true">/</span>
      <span aria-current="page">Resoluciones de alcaldía</span>
    </nav>

    <section class="resoluciones-container" aria-labelledby="resoluciones-title">
      <header class="resoluciones-header">
        <h1 id="resoluciones-title"><i class="fas fa-file-signature" aria-hidden="true"></i> Resoluciones de Alcaldía</h1>
        <p class="resoluciones-subtitle">
          Consulta y descarga las resoluciones oficiales emitidas por la Alcaldía de la Municipalidad Distrital de Lamay.
        </p>
        <div class="search-container">
          <i class="fas fa-search" aria-hidden="true"></i>
          <label class="sr-only" for="search-resoluciones">Buscar resoluciones</label>
          <input type="search" id="search-resoluciones" placeholder="Buscar por número o año..." autocomplete="off">
        </div>
      </header>

      <div class="filters-container-resoluciones">
        <div class="filtro-anios" id="filtros-anios-resoluciones" aria-label="Filtrar resoluciones por año"></div>
        <div class="sort-container">
          <label for="sort-resoluciones">Ordenar:</label>
          <select id="sort-resoluciones">
            <option value="fecha_desc">Fecha (reciente primero)</option>
            <option value="fecha_asc">Fecha (antiguo primero)</option>
            <option value="numero_desc">Número descendente</option>
            <option value="numero_asc">Número ascendente</option>
          </select>
        </div>
      </div>

      <div class="resoluciones-grid" id="resoluciones-grid" aria-live="polite" aria-busy="true">
        <div class="loading">
          <span class="loading-spinner" aria-hidden="true"></span>
          <p>Cargando resoluciones de alcaldía...</p>
        </div>
      </div>
    </section>
  </div>
</main>
<script src="<?= $baseUrl ?>/assets/js/resoluciones-alcaldia.js?v=<?= filemtime(dirname(__DIR__).'/assets/js/resoluciones-alcaldia.js') ?>" defer></script>
<?php require dirname(__DIR__).'/includes/footer.php'; ?>

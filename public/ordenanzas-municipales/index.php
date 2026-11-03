<?php
require dirname(__DIR__).'/includes/bootstrap.php';
$pageTitle = 'Ordenanzas municipales';
$pageDescription = 'Consulta y descarga las ordenanzas oficiales emitidas por la Municipalidad Distrital de Lamay.';
$pageStyles = ['ordenanzas-municipales.css'];
require dirname(__DIR__).'/includes/header.php';
?>
<main class="page-main ordenanzas-page">
  <div class="container">
    <nav class="breadcrumbs" aria-label="Ruta de navegación">
      <a href="<?= $baseUrl ?>/">Inicio</a>
      <span aria-hidden="true">/</span>
      <span>Transparencia</span>
      <span aria-hidden="true">/</span>
      <span aria-current="page">Ordenanzas municipales</span>
    </nav>

    <section class="ordenanzas-container" aria-labelledby="ordenanzas-title">
      <header class="ordenanzas-header">
        <h1 id="ordenanzas-title"><i class="fas fa-file-contract" aria-hidden="true"></i> Ordenanzas Municipales</h1>
        <p class="ordenanzas-subtitle">
          Consulta y descarga las ordenanzas oficiales emitidas por la Municipalidad Distrital de Lamay.
        </p>
        <div class="ordenanzas-search">
          <i class="fas fa-search" aria-hidden="true"></i>
          <label class="sr-only" for="search-ordenanzas">Buscar ordenanzas</label>
          <input type="search" id="search-ordenanzas" placeholder="Buscar por número o año..." autocomplete="off">
        </div>
      </header>

      <div class="ordenanzas-filters">
        <div class="ordenanzas-year-filters" id="filtros-anios-ordenanzas" aria-label="Filtrar ordenanzas por año"></div>
        <div class="ordenanzas-sort">
          <label for="sort-ordenanzas">Ordenar:</label>
          <select id="sort-ordenanzas">
            <option value="fecha_desc">Fecha (reciente primero)</option>
            <option value="fecha_asc">Fecha (antiguo primero)</option>
            <option value="numero_desc">Número descendente</option>
            <option value="numero_asc">Número ascendente</option>
          </select>
        </div>
      </div>

      <div class="ordenanzas-grid" id="ordenanzas-grid" aria-live="polite" aria-busy="true">
        <div class="loading">
          <span class="loading-spinner" aria-hidden="true"></span>
          <p>Cargando ordenanzas municipales...</p>
        </div>
      </div>
    </section>
  </div>
</main>
<script src="<?= $baseUrl ?>/assets/js/ordenanzas-municipales.js?v=<?= filemtime(dirname(__DIR__).'/assets/js/ordenanzas-municipales.js') ?>" defer></script>
<?php require dirname(__DIR__).'/includes/footer.php'; ?>

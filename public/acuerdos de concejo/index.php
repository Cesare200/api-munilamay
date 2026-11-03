<?php
require dirname(__DIR__) . '/includes/bootstrap.php';
$pageTitle = 'Acuerdos de concejo';
$pageDescription = 'Consulta y descarga los acuerdos oficiales emitidos por el Concejo Municipal de Lamay.';
$pageStyles = ['acuerdos-concejo.css'];
require dirname(__DIR__) . '/includes/header.php';
?>
<main class="page-main acuerdos-page">
  <div class="container">
    <nav class="breadcrumbs" aria-label="Ruta de navegación">
      <a href="<?= $baseUrl ?>/">Inicio</a>
      <span aria-hidden="true">/</span>
      <span>Transparencia</span>
      <span aria-hidden="true">/</span>
      <span aria-current="page">Acuerdos de concejo</span>
    </nav>

    <section class="acuerdos-container" aria-labelledby="acuerdos-title">
      <header class="acuerdos-header">
        <h1 id="acuerdos-title"><i class="fas fa-handshake" aria-hidden="true"></i> Acuerdos de Concejo</h1>
        <p class="acuerdos-subtitle">
          Consulta y descarga los acuerdos oficiales emitidos por el Concejo Municipal de Lamay.
        </p>
        <div class="search-container">
          <i class="fas fa-search" aria-hidden="true"></i>
          <label class="sr-only" for="search-acuerdos">Buscar acuerdos</label>
          <input type="search" id="search-acuerdos" placeholder="Buscar por número, año o descripción..." autocomplete="off">
        </div>
      </header>

      <div class="filters-container-acuerdos">
        <div class="filtro-anios" id="filtros-anios-acuerdos" aria-label="Filtrar acuerdos por año"></div>
        <div class="sort-container">
          <label for="sort-acuerdos">Ordenar:</label>
          <select id="sort-acuerdos">
            <option value="fecha_desc">Fecha (reciente primero)</option>
            <option value="fecha_asc">Fecha (antiguo primero)</option>
            <option value="numero_desc">Número descendente</option>
            <option value="numero_asc">Número ascendente</option>
          </select>
        </div>
      </div>

      <div class="acuerdos-grid" id="acuerdos-grid" aria-live="polite" aria-busy="true">
        <div class="loading">
          <span class="loading-spinner" aria-hidden="true"></span>
          <p>Cargando acuerdos...</p>
        </div>
      </div>
    </section>
  </div>
</main>
<script src="<?= $baseUrl ?>/assets/js/acuerdos-concejo.js?v=<?= filemtime(dirname(__DIR__) . '/assets/js/acuerdos-concejo.js') ?>" defer></script>
<?php require dirname(__DIR__) . '/includes/footer.php'; ?>

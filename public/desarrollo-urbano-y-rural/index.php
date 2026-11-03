<?php
require dirname(__DIR__).'/includes/bootstrap.php';
$pageTitle = 'Ordenamiento territorial';
$pageDescription = 'Consulta y descarga los documentos del Esquema de Acondicionamiento Urbano de Lamay.';
$pageStyles = ['ordenamiento-territorial.css'];
require dirname(__DIR__).'/includes/header.php';
?>
<main class="page-main ordenamiento-page">
  <div class="container">
    <nav class="breadcrumbs" aria-label="Ruta de navegación">
      <a href="<?= $baseUrl ?>/">Inicio</a>
      <span aria-hidden="true">/</span>
      <span>Servicios</span>
      <span aria-hidden="true">/</span>
      <span aria-current="page">Ordenamiento Territorial</span>
    </nav>

    <section class="ordenamiento-container" aria-labelledby="ordenamiento-title">
      <header class="ordenamiento-header">
        <h1 id="ordenamiento-title">
          <i class="fas fa-map-marked-alt" aria-hidden="true"></i>
          <span>Ordenamiento Territorial</span>
        </h1>
        <p class="ordenamiento-subtitle">
          Esquema de Acondicionamiento Urbano (2026 - 2036) del Centro Poblado de Lamay. Consulta y descarga los tomos y mapas oficiales.
        </p>
        <div class="search-container">
          <i class="fas fa-search" aria-hidden="true"></i>
          <label class="sr-only" for="search-ordenamiento">Buscar documentos</label>
          <input type="search" id="search-ordenamiento" placeholder="Buscar por título, tomo, subcategoría o descripción..." autocomplete="off">
        </div>
      </header>

      <div class="filters-container-ordenamiento">
        <div class="filtro-categorias" id="filtros-categorias-ordenamiento" aria-label="Filtrar por categoría"></div>
        <div class="sort-container">
          <label for="sort-ordenamiento">Ordenar por:</label>
          <select id="sort-ordenamiento">
            <option value="titulo_asc">Título (A - Z)</option>
            <option value="titulo_desc">Título (Z - A)</option>
            <option value="reciente">Más reciente</option>
          </select>
        </div>
      </div>

      <div class="ordenamiento-grid" id="ordenamiento-grid" aria-live="polite" aria-busy="true">
        <div class="loading">
          <span class="loading-spinner" aria-hidden="true"></span>
          <p>Cargando documentos de ordenamiento territorial...</p>
        </div>
      </div>
    </section>
  </div>
</main>
<script src="<?= $baseUrl ?>/assets/js/ordenamiento-territorial.js?v=<?= filemtime(dirname(__DIR__).'/assets/js/ordenamiento-territorial.js') ?>" defer></script>
<?php require dirname(__DIR__).'/includes/footer.php'; ?>

<?php
require dirname(__DIR__) . '/includes/bootstrap.php';
$pageTitle = 'Procesos de selección';
$pageDescription = 'Consulta los procesos de selección y descarga sus bases, convocatorias y documentos publicados por la Municipalidad Distrital de Lamay.';
$pageStyles = ['procesos-seleccion.css'];
require dirname(__DIR__) . '/includes/header.php';
?>
<main class="page-main procesos-seleccion-page">
  <div class="container">
    <nav class="breadcrumbs" aria-label="Ruta de navegación">
      <a href="<?= $baseUrl ?>/">Inicio</a>
      <span aria-hidden="true">/</span>
      <span>Transparencia</span>
      <span aria-hidden="true">/</span>
      <span aria-current="page">Procesos de selección</span>
    </nav>

    <section class="proceso-container" aria-labelledby="titulo-seccion">
      <header class="proceso-header">
        <h1>
          <i class="fas fa-file-contract" aria-hidden="true"></i>
          <span id="titulo-seccion">Convocatoria del Proceso de Selección</span>
        </h1>
        <p class="proceso-subtitle" id="entidad-nombre">Cargando entidad convocante...</p>
      </header>

      <div class="filters-container" id="filters-container" aria-label="Filtrar procesos de selección" aria-busy="true">
        <span class="filters-loading"><i class="fas fa-spinner fa-spin" aria-hidden="true"></i> Cargando procesos...</span>
      </div>

      <div class="proceso-grid" id="proceso-grid" aria-live="polite" aria-busy="true">
        <p class="proceso-empty">
          <i class="fas fa-spinner fa-spin" aria-hidden="true"></i> Cargando documentos...
        </p>
      </div>
    </section>
  </div>
</main>

<div class="proceso-modal" id="proceso-modal" aria-hidden="true">
  <section class="proceso-modal-content" role="dialog" aria-modal="true" aria-labelledby="modal-title" tabindex="-1">
    <button class="proceso-modal-close" type="button" aria-label="Cerrar vista previa">
      <i class="fas fa-times" aria-hidden="true"></i>
    </button>
    <h2 id="modal-title">Documento del proceso</h2>
    <div class="proceso-modal-body" id="modal-body"></div>
    <footer class="proceso-modal-footer">
      <a href="#" class="proceso-download-link" id="modal-download-link" target="_blank" rel="noopener noreferrer">
        <i class="fas fa-download" aria-hidden="true"></i> Descargar documento completo
      </a>
    </footer>
  </section>
</div>

<script src="<?= $baseUrl ?>/assets/js/procesos-seleccion.js?v=<?= filemtime(dirname(__DIR__) . '/assets/js/procesos-seleccion.js') ?>" defer></script>
<?php require dirname(__DIR__) . '/includes/footer.php'; ?>

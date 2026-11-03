<?php
require dirname(__DIR__).'/includes/bootstrap.php';
$pageTitle = 'Otros documentos municipales';
$pageDescription = 'Consulta documentos institucionales y de interés público de la Municipalidad Distrital de Lamay.';
$pageStyles = ['otros-documentos-interes.css'];
require dirname(__DIR__).'/includes/header.php';
?>
<main class="page-main otrosdocs-page">
  <div class="container">
    <nav class="breadcrumbs" aria-label="Ruta de navegación">
      <a href="<?= $baseUrl ?>/">Inicio</a>
      <span aria-hidden="true">/</span>
      <span>Transparencia</span>
      <span aria-hidden="true">/</span>
      <span aria-current="page">Otros documentos de interés</span>
    </nav>

    <section class="otrosdocs-container" aria-labelledby="otrosdocs-title">
      <header class="otrosdocs-header">
        <h1 id="otrosdocs-title"><i class="fas fa-file-pdf" aria-hidden="true"></i> Otros documentos municipales</h1>
        <p class="otrosdocs-subtitle">Documentos institucionales y de interés público de la Municipalidad Distrital de Lamay</p>
      </header>

      <div class="otrosdocs-grid" id="otrosdocsGrid" aria-live="polite" aria-busy="true">
        <p class="otrosdocs-loading"><i class="fas fa-spinner fa-spin" aria-hidden="true"></i> Cargando documentos...</p>
      </div>

      <footer class="otrosdocs-footer">
        <i class="fas fa-landmark" aria-hidden="true"></i> Municipalidad Distrital de Lamay
      </footer>
    </section>
  </div>
</main>
<script src="<?= $baseUrl ?>/assets/js/otros-documentos-interes.js?v=<?= filemtime(dirname(__DIR__).'/assets/js/otros-documentos-interes.js') ?>" defer></script>
<?php require dirname(__DIR__).'/includes/footer.php'; ?>

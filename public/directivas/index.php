<?php
require dirname(__DIR__).'/includes/bootstrap.php';
$pageTitle = 'Directivas municipales';
$pageDescription = 'Consulta las directivas internas de la Municipalidad Distrital de Lamay.';
$pageStyles = ['directivas.css'];
require dirname(__DIR__).'/includes/header.php';
?>
<main class="page-main directivas-page">
  <div class="container">
    <nav class="breadcrumbs" aria-label="Ruta de navegación">
      <a href="<?= $baseUrl ?>/">Inicio</a>
      <span aria-hidden="true">/</span>
      <span>Transparencia</span>
      <span aria-hidden="true">/</span>
      <span aria-current="page">Directivas municipales</span>
    </nav>

    <section class="directivas-municipales" id="directivasContainer" aria-labelledby="directivas-title">
      <header class="directivas-header">
        <h1 id="directivas-title"><i class="fas fa-file-alt" aria-hidden="true"></i> Directivas Municipales</h1>
        <p class="directivas-subtitle">Normativa y directivas internas vigentes de la Municipalidad Distrital de Lamay</p>
      </header>

      <div class="directivas-lista" id="directivasLista" aria-live="polite" aria-busy="true">
        <p class="directivas-loading"><i class="fas fa-spinner fa-spin" aria-hidden="true"></i> Cargando directivas...</p>
      </div>

      <footer class="directivas-footer">
        <i class="fas fa-landmark" aria-hidden="true"></i> Municipalidad Distrital de Lamay
      </footer>
    </section>
  </div>
</main>
<script src="<?= $baseUrl ?>/assets/js/directivas.js?v=<?= filemtime(dirname(__DIR__).'/assets/js/directivas.js') ?>" defer></script>
<?php require dirname(__DIR__).'/includes/footer.php'; ?>

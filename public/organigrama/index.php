<?php
require dirname(__DIR__).'/includes/bootstrap.php';
$pageTitle = 'Organigrama';
$pageDescription = 'Estructura orgánica de la Municipalidad Distrital de Lamay.';
$pageIntro = 'Organigrama institucional';
$pageLead = 'Consulta la estructura y organización de la Municipalidad Distrital de Lamay.';
$pageSection = 'Municipalidad';
require dirname(__DIR__).'/includes/header.php';
?>
<main class="page-main">
  <div class="container">
    <nav class="breadcrumbs" aria-label="Ruta de navegación"><a href="<?= $baseUrl ?>/">Inicio</a><span aria-hidden="true">/</span><span>Municipalidad</span><span aria-hidden="true">/</span><span>Organigrama</span></nav>
    <h1 class="page-title"><?= htmlspecialchars($pageIntro, ENT_QUOTES, 'UTF-8') ?></h1>
    <p class="page-lead"><?= htmlspecialchars($pageLead, ENT_QUOTES, 'UTF-8') ?></p>
    <section class="page-panel">
      <h2>Estructura orgánica</h2>
      <p>El organigrama aprobado permite identificar los órganos, unidades y relaciones de dependencia de la institución.</p>
      <p>El gráfico y el documento de gestión vigentes se publicarán en este espacio cuando se incorporen sus archivos oficiales.</p>
      <a class="back-link" href="<?= $baseUrl ?>/organigrama/" target="_blank" rel="noopener noreferrer">Consultar la página oficial →</a>
    </section>
  </div>
</main>
<?php require dirname(__DIR__).'/includes/footer.php'; ?>

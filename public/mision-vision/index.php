<?php
require dirname(__DIR__).'/includes/bootstrap.php';
$management = muni_api_data('gestion');
$mission = is_array($management) && is_string($management['mision'] ?? null) ? trim($management['mision']) : '';
$vision = is_array($management) && is_string($management['vision'] ?? null) ? trim($management['vision']) : '';
$missionParagraphs = $mission !== '' ? preg_split('/\R+/', $mission, -1, PREG_SPLIT_NO_EMPTY) : [];
$visionParagraphs = $vision !== '' ? preg_split('/\R+/', $vision, -1, PREG_SPLIT_NO_EMPTY) : [];
$pageTitle = 'Misión y visión';
$pageDescription = 'Misión y visión de la Municipalidad Distrital de Lamay.';
$pageIntro = 'Misión y visión';
$pageStyles = ['mision-vision.css'];
require dirname(__DIR__).'/includes/header.php';
?>
<main class="page-main mision-vision-page">
  <div class="container">
    <nav class="breadcrumbs" aria-label="Ruta de navegación">
      <a href="<?= $baseUrl ?>/">Inicio</a>
      <span aria-hidden="true">/</span>
      <span>Municipalidad</span>
      <span aria-hidden="true">/</span>
      <span aria-current="page">Misión y visión</span>
    </nav>

    <section class="muni-mv-section" aria-labelledby="muni-mv-title">
      <div class="muni-mv-container">
        <header class="muni-mv-header">
          <span class="muni-mv-subtitle">Horizonte y Compromiso</span>
          <h1 id="muni-mv-title">Misión y Visión</h1>
          <div class="muni-mv-decorative-line" aria-hidden="true"></div>
        </header>

        <div class="mv-cards-grid">
          <article class="mv-card mision-card">
            <div class="mv-icon-box" aria-hidden="true">
              <i class="fas fa-bullseye"></i>
            </div>
            <div class="mv-card-body">
              <span class="mv-card-tag">Nuestro Propósito</span>
              <h2 class="mv-card-title">Misión Institucional</h2>
              <div class="mv-card-divider" aria-hidden="true"></div>
              <div class="mv-card-text">
                <?php if ($missionParagraphs !== []) { ?>
                  <?php foreach ($missionParagraphs as $paragraph) { ?>
                <p><?= muni_escape(trim($paragraph)) ?></p>
                  <?php } ?>
                <?php } else { ?>
                <p>La información de la misión institucional no está disponible en este momento.</p>
                <?php } ?>
              </div>
            </div>
            <div class="mv-card-accent" aria-hidden="true"></div>
          </article>

          <article class="mv-card vision-card">
            <div class="mv-icon-box" aria-hidden="true">
              <i class="fas fa-eye"></i>
            </div>
            <div class="mv-card-body">
              <span class="mv-card-tag">Nuestro Futuro</span>
              <h2 class="mv-card-title">Visión Estratégica</h2>
              <div class="mv-card-divider" aria-hidden="true"></div>
              <div class="mv-card-text">
                <?php if ($visionParagraphs !== []) { ?>
                  <?php foreach ($visionParagraphs as $paragraph) { ?>
                <p><?= muni_escape(trim($paragraph)) ?></p>
                  <?php } ?>
                <?php } else { ?>
                <p>La información de la visión institucional no está disponible en este momento.</p>
                <?php } ?>
              </div>
            </div>
            <div class="mv-card-accent" aria-hidden="true"></div>
          </article>
        </div>
      </div>
    </section>
  </div>
</main>
<?php require dirname(__DIR__).'/includes/footer.php'; ?>

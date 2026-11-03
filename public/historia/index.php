<?php
require dirname(__DIR__).'/includes/bootstrap.php';
$management = muni_api_data('gestion');
$historyHtml = is_array($management) && is_string($management['historia'] ?? null) ? trim($management['historia']) : '';
$historyParagraphs = [];

if ($historyHtml !== '' && class_exists(DOMDocument::class)) {
    $historyDocument = new DOMDocument('1.0', 'UTF-8');
    $historyDocument->loadHTML(
        '<!DOCTYPE html><html><head><meta charset="UTF-8"></head><body>'.$historyHtml.'</body></html>',
        LIBXML_NONET | LIBXML_NOERROR | LIBXML_NOWARNING
    );
    foreach ($historyDocument->getElementsByTagName('p') as $paragraph) {
        $text = trim($paragraph->textContent);
        if ($text !== '') {
            $historyParagraphs[] = $text;
        }
    }
    if ($historyParagraphs === []) {
        $text = trim($historyDocument->getElementsByTagName('body')->item(0)?->textContent ?? '');
        if ($text !== '') {
            $historyParagraphs[] = $text;
        }
    }
}

$galleryPhotos = [];
$photos = is_array($management) && is_array($management['fotos_gestion'] ?? null)
    ? $management['fotos_gestion']
    : [];
foreach ($photos as $photo) {
    $photoUrl = muni_http_url($photo);
    if ($photoUrl !== null) {
        $galleryPhotos[] = $photoUrl;
    }
}
$galleryPhotos = array_values(array_unique($galleryPhotos));
shuffle($galleryPhotos);
$galleryPhotos = array_slice($galleryPhotos, 0, 3);

$pageTitle = 'Historia de Lamay';
$pageDescription = 'Conoce la historia de Lamay y la galería institucional de la Municipalidad Distrital.';
$pageIntro = 'Historia de Lamay';
$pageStyles = ['historia.css'];
require dirname(__DIR__).'/includes/header.php';
?>
<main class="page-main historia-page">
  <div class="container">
    <nav class="breadcrumbs" aria-label="Ruta de navegación">
      <a href="<?= $baseUrl ?>/">Inicio</a>
      <span aria-hidden="true">/</span>
      <span>Municipalidad</span>
      <span aria-hidden="true">/</span>
      <span aria-current="page">Historia</span>
    </nav>

    <section class="muni-gestion-section" id="seccion-historia-galeria" aria-label="Historia y galería institucional">
      <div class="gestion-container">
        <article class="gestion-card historia-card">
          <header class="card-header">
            <i class="fas fa-book-open" aria-hidden="true"></i>
            <h1>Historia de Lamay</h1>
          </header>
          <div class="historia-content">
            <?php if ($historyParagraphs !== []) { ?>
              <?php foreach ($historyParagraphs as $paragraph) { ?>
            <p><?= muni_escape($paragraph) ?></p>
              <?php } ?>
            <?php } else { ?>
            <p class="content-unavailable">La información de historia no está disponible en este momento.</p>
            <?php } ?>
          </div>
        </article>

        <section class="gestion-card galeria-card" aria-labelledby="galeria-title">
          <header class="card-header">
            <i class="fas fa-images" aria-hidden="true"></i>
            <h2 id="galeria-title">Galería de Gestión</h2>
            <span class="subtitle-galeria">Momentos de nuestra comunidad</span>
          </header>
          <?php if ($galleryPhotos !== []) { ?>
          <div class="galeria-grid">
            <?php foreach ($galleryPhotos as $index => $photoUrl) { ?>
            <figure class="galeria-item">
              <img src="<?= muni_escape($photoUrl) ?>" alt="Galería de gestión municipal de Lamay, imagen <?= $index + 1 ?>" loading="lazy">
            </figure>
            <?php } ?>
          </div>
          <?php } else { ?>
          <p class="content-unavailable">No hay imágenes disponibles en la galería.</p>
          <?php } ?>
        </section>
      </div>
    </section>
  </div>
</main>
<?php require dirname(__DIR__).'/includes/footer.php'; ?>
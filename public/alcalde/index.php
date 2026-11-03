<?php
require dirname(__DIR__).'/includes/bootstrap.php';
$management = muni_api_data('gestion');
$mayor = is_array($management) && is_array($management['alcalde'] ?? null) ? $management['alcalde'] : [];
$council = is_array($management) && is_array($management['concejo'] ?? null) ? $management['concejo'] : [];
$mayorName = trim((string) ($mayor['nombre'] ?? ''));
$mayorNameParts = $mayorName !== '' ? preg_split('/\s+/', $mayorName) : [];
$mayorNameSplit = $mayorName === ''
    ? ['Datos de alcaldía', 'no disponibles']
    : (count($mayorNameParts) > 2
        ? [implode(' ', array_slice($mayorNameParts, 0, -2)), implode(' ', array_slice($mayorNameParts, -2))]
        : ['SR.', $mayorName]);
$mayorPhoto = muni_http_url($mayor['foto_url'] ?? null);
$motto = trim((string) ($management['eslogan'] ?? ''));
$pageTitle = 'Alcalde y Concejo Municipal';
$pageDescription = 'Conoce a las autoridades de la Municipalidad Distrital de Lamay.';
$pageStyles = ['alcalde.css'];

require dirname(__DIR__).'/includes/header.php';
?>
<main class="page-main alcalde-page">
  <div class="container">
    <nav class="breadcrumbs" aria-label="Ruta de navegación">
      <a href="<?= $baseUrl ?>/">Inicio</a>
      <span aria-hidden="true">/</span>
      <span>Municipalidad</span>
      <span aria-hidden="true">/</span>
      <span aria-current="page">Alcalde</span>
    </nav>

    <section class="alcalde-precise-container" aria-labelledby="alcalde-nombre">
      <div class="alcalde-precise-card">
        <div class="precise-foto-frame" id="alcalde-frame">
          <?php if ($mayorPhoto === null) { ?>
          <div class="precise-avatar" aria-hidden="true">
            <i class="fas fa-user-tie"></i>
          </div>
          <?php } ?>
          <?php if ($mayorPhoto !== null) { ?>
          <img
            class="precise-foto"
            src="<?= muni_escape($mayorPhoto) ?>"
            alt="<?= muni_escape(($mayor['cargo'] ?? 'Alcalde Distrital').($mayorName !== '' ? ' - '.$mayorName : '')) ?>"
            loading="lazy"
          >
          <?php } ?>
          <div class="precise-photo-shine" aria-hidden="true"></div>
        </div>

        <div class="precise-content">
          <p class="precise-badge"><?= muni_escape($mayor['cargo'] ?? 'Alcalde Distrital') ?></p>
          <h1 class="precise-title" id="alcalde-nombre">
            <?php foreach ($mayorNameSplit as $nameLine) { ?>
            <span><?= muni_escape($nameLine) ?></span>
            <?php } ?>
          </h1>
          <h2 class="precise-subtitle">Municipalidad Distrital de Lamay</h2>
          <div class="precise-divider" aria-hidden="true"></div>
          <p class="precise-text"><?= muni_escape($mayor['mensaje'] ?? 'El mensaje institucional no está disponible en este momento.') ?></p>
          <?php if ($motto !== '') { ?>
          <blockquote class="precise-quote">
            <span class="quote-mark" aria-hidden="true">“</span>
            <span><?= muni_escape($motto) ?></span>
          </blockquote>
          <?php } ?>
        </div>
      </div>
    </section>

    <section class="concejo-container" aria-labelledby="concejo-title">
      <header class="concejo-header">
        <p class="section-eyebrow">Autoridades municipales</p>
        <h2 id="concejo-title">Concejo Municipal</h2>
        <div class="decorative-line" aria-hidden="true"></div>
      </header>
      <div class="regidores-grid">
        <?php if ($council === []) { ?>
        <p class="concejo-empty">La información del concejo municipal no está disponible en este momento.</p>
        <?php } else { ?>
          <?php foreach ($council as $member) { ?>
            <?php
            if (! is_array($member)) {
                continue;
            }
              $memberName = trim((string) ($member['nombre'] ?? ''));
              $memberPhoto = muni_http_url($member['foto_url'] ?? null);
              ?>
            <article class="regidor-card">
              <div class="regidor-img-container">
                <?php if ($memberPhoto !== null) { ?>
                <img src="<?= muni_escape($memberPhoto) ?>" alt="<?= muni_escape($memberName !== '' ? $memberName : 'Integrante del concejo') ?>" loading="lazy">
                <?php } else { ?>
                <div class="regidor-avatar-placeholder" aria-hidden="true"><i class="fas fa-user-tie"></i></div>
                <?php } ?>
              </div>
              <div class="regidor-info">
                <h3><?= muni_escape($memberName !== '' ? $memberName : 'Integrante del concejo') ?></h3>
                <p class="regidor-cargo"><?= muni_escape($member['cargo'] ?? 'Regidor(a)') ?></p>
              </div>
            </article>
          <?php } ?>
        <?php } ?>
      </div>
    </section>
  </div>
</main>
<?php require dirname(__DIR__).'/includes/footer.php'; ?>

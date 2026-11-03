<?php
$telefono = trim((string) ($institutional['telefono_principal'] ?? ''));
$email = trim((string) ($institutional['email_principal'] ?? ''));
$direccionCorta = trim((string) ($institutional['direccion_corta'] ?? ''));
$telHref = muni_phone_href($telefono);
$mapaUrl = muni_http_url($institutional['mapa_url'] ?? null);
$whatsapp = preg_replace('/\D+/', '', (string) ($institutional['whatsapp_numero'] ?? ''));
$facebookUrl = muni_http_url($institutional['facebook_url'] ?? null);
$tiktokUrl = muni_http_url($institutional['tiktok_url'] ?? null);
$youtubeUrl = muni_http_url($institutional['youtube_url'] ?? null);
$logoUrl = muni_http_url($institutional['logo_url'] ?? null);
?>
<header class="muni-header inner-muni-header">
  <div class="top-bar">
    <div class="top-bar-content">
      <div class="contact-info">
        <?php if ($telefono !== '') { ?>
        <span class="contact-item">
          <i class="fas fa-phone" aria-hidden="true"></i>
          <?php if ($telHref !== null) { ?><a href="<?= muni_escape($telHref) ?>"><?= muni_escape($telefono) ?></a><?php } else { ?><span><?= muni_escape($telefono) ?></span><?php } ?>
        </span>
        <?php } ?>
        <?php if ($email !== '') { ?>
        <span class="contact-item">
          <i class="fas fa-envelope" aria-hidden="true"></i>
          <a href="mailto:<?= muni_escape($email) ?>"><?= muni_escape($email) ?></a>
        </span>
        <?php } ?>
        <?php if ($direccionCorta !== '') { ?>
        <span class="contact-item">
          <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
          <?php if ($mapaUrl !== null) { ?><a href="<?= muni_escape($mapaUrl) ?>" target="_blank" rel="noopener noreferrer"><?= muni_escape($direccionCorta) ?></a><?php } else { ?><?= muni_escape($direccionCorta) ?><?php } ?>
        </span>
        <?php } ?>
      </div>
      <div class="social-icons" aria-label="Redes sociales">
        <?php if ($facebookUrl !== null) { ?><a href="<?= muni_escape($facebookUrl) ?>" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><i class="fab fa-facebook-f" aria-hidden="true"></i></a><?php } ?>
        <?php if ($whatsapp !== '') { ?><a href="https://wa.me/<?= str_starts_with($whatsapp, '51') ? muni_escape($whatsapp) : '51'.muni_escape($whatsapp) ?>" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp"><i class="fab fa-whatsapp" aria-hidden="true"></i></a><?php } ?>
        <?php if ($tiktokUrl !== null) { ?><a href="<?= muni_escape($tiktokUrl) ?>" target="_blank" rel="noopener noreferrer" aria-label="TikTok"><i class="fab fa-tiktok" aria-hidden="true"></i></a><?php } ?>
        <?php if ($youtubeUrl !== null) { ?><a href="<?= muni_escape($youtubeUrl) ?>" target="_blank" rel="noopener noreferrer" aria-label="YouTube"><i class="fab fa-youtube" aria-hidden="true"></i></a><?php } ?>
      </div>
    </div>
  </div>

  <nav class="nav-bar" id="main-navbar" aria-label="Navegación principal">
    <div class="nav-content">
      <a class="inner-brand" href="<?= $baseUrl ?>/" aria-label="Inicio - Municipalidad Distrital de Lamay">
        <?php if ($logoUrl !== null) { ?>
        <img class="inner-brand-img" src="<?= muni_escape($logoUrl) ?>" alt="" loading="lazy">
        <?php } else { ?>
        <span class="inner-brand-mark" aria-hidden="true"><i class="fas fa-mountain"></i></span>
        <?php } ?>
        <span class="inner-brand-copy">
          <strong>MUNICIPALIDAD DISTRITAL</strong>
          <small>DE LAMAY · CUSCO</small>
        </span>
      </a>

      <button class="menu-toggle" id="menu-toggle-btn" type="button" aria-label="Abrir menú" aria-expanded="false" aria-controls="nav-links">
        <i class="fas fa-bars" aria-hidden="true"></i>
      </button>

      <div class="nav-center">
        <div class="nav-links" id="nav-links">
          <div class="dropdown">
            <button type="button" class="nav-item" aria-expanded="false" aria-controls="inner-sub-municipalidad"><i class="fas fa-building" aria-hidden="true"></i><span>Municipalidad</span></button>
            <div class="dropdown-content" id="inner-sub-municipalidad">
              <div class="dropdown-section">
                <a href="<?= $baseUrl ?>/alcalde/" class="dropdown-link">Alcalde y regidores</a>
                <a href="<?= $baseUrl ?>/mision-vision/" class="dropdown-link">Misión y visión</a>
                <a href="<?= $baseUrl ?>/historia/" class="dropdown-link">Historia</a>
                <a href="<?= $baseUrl ?>/organigrama/" class="dropdown-link">Organigrama</a>
              </div>
            </div>
          </div>

          <div class="dropdown">
            <button type="button" class="nav-item" aria-expanded="false" aria-controls="inner-sub-servicios"><i class="fas fa-hands-helping" aria-hidden="true"></i><span>Servicios</span></button>
            <div class="dropdown-content" id="inner-sub-servicios">
              <div class="dropdown-section">
                <a href="<?= $baseUrl ?>/demuna/" class="dropdown-link">DEMUNA</a>
                <a href="<?= $baseUrl ?>/codisec/" class="dropdown-link">CODISEC</a>
                <a href="<?= $baseUrl ?>/desarrollo-urbano-y-rural/" class="dropdown-link">Desarrollo urbano y rural</a>
              </div>
            </div>
          </div>

          <div class="dropdown">
            <button type="button" class="nav-item" aria-expanded="false" aria-controls="inner-sub-transparencia"><i class="fas fa-file-alt" aria-hidden="true"></i><span>Transparencia</span></button>
            <div class="dropdown-content" id="inner-sub-transparencia">
              <div class="dropdown-section">
                <div class="dropdown-section-title">Documentos normativos</div>
                <a href="<?= $baseUrl ?>/acuerdos%20de%20concejo/" class="dropdown-link">Acuerdos de concejo</a>
                <a href="<?= $baseUrl ?>/resoluciones-de-alcaldia/" class="dropdown-link">Resoluciones de alcaldía</a>
                <a href="<?= $baseUrl ?>/decreto-de-alcaldia/" class="dropdown-link">Decretos de alcaldía</a>
                <a href="<?= $baseUrl ?>/ordenanzas-municipales/" class="dropdown-link">Ordenanzas municipales</a>
                <a href="<?= $baseUrl ?>/resoluciones-de-gerencia/" class="dropdown-link">Resoluciones de gerencia</a>
                <a href="<?= $baseUrl ?>/directivas/" class="dropdown-link">Directivas municipales</a>
              </div>
              <div class="dropdown-section">
                <div class="dropdown-section-title">Gestión y convocatorias</div>
                <a href="<?= $baseUrl ?>/instrumentos_gestion/" class="dropdown-link">Instrumentos de gestión</a>
                <a href="<?= $baseUrl ?>/convocatorias-cas/" class="dropdown-link">Convocatorias CAS</a>
              </div>
              <div class="dropdown-section">
                <div class="dropdown-section-title">Otros documentos</div>
                <a href="<?= $baseUrl ?>/otros-documentos-de-interes/" class="dropdown-link">Documentos de interés</a>
                <a href="<?= $baseUrl ?>/proceso%20de%20seleccion/" class="dropdown-link">Procesos de selección</a>
                <a href="<?= $baseUrl ?>/Interaccion%20con%20el%20mercado/" class="dropdown-link">Interacción con el mercado</a>
              </div>
            </div>
            <a class="portal-transparencia portal-transparencia--mobile" href="<?= $baseUrl ?>/acuerdos%20de%20concejo/">Portal de transparencia <span aria-hidden="true">→</span></a>
          </div>
        </div>
      </div>

      <a class="portal-transparencia" href="<?= $baseUrl ?>/acuerdos%20de%20concejo/">Portal de transparencia <span aria-hidden="true">→</span></a>
    </div>
  </nav>
</header>

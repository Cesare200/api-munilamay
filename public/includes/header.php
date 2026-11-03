<?php
require_once __DIR__.'/bootstrap.php';
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap"/>
<link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/header.css">
<?php foreach (($pageStyles ?? []) as $pageStyle) { ?>
  <?php if (is_string($pageStyle) && preg_match('/\A[a-zA-Z0-9_-]+\.css\z/', $pageStyle)) { ?>
    <?php $pageStylePath = dirname(__DIR__).'/assets/css/'.$pageStyle; ?>
    <?php if (is_file($pageStylePath)) { ?>
<link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/<?= muni_escape($pageStyle) ?>?v=<?= filemtime($pageStylePath) ?>">
    <?php } ?>
  <?php } ?>
<?php } ?>

<div class="muni-header">
    <!-- Barra superior -->
    <div class="top-bar">
        <div class="top-bar-content">
            <div class="contact-info">
                <span class="contact-item">
                    <i class="fas fa-phone"></i>
                    957 756 538
                </span>
                <span class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <a href="mailto:mesadepartes@munilamay.gob.pe">mesadepartes@munilamay.gob.pe</a>
                </span>
                <span class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    Plaza De Armas Lamay
                </span>
            </div>
            <div class="social-icons">
                <a href="https://www.facebook.com/profile.php?id=61562288742277" target="_blank" rel="noopener" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="http://wa.me/+51957756538" target="_blank" rel="noopener" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                <a href="https://www.tiktok.com/@municipalidadlamay?lang=es" target="_blank" rel="noopener" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
                <a href="#" target="_blank" rel="noopener" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
    </div>

    <!-- Navbar principal -->
    <nav class="nav-bar">
        <div class="nav-content">
            <a href="../index.php" class="logo-link" aria-label="Inicio">
                <img src="/assets/images/lamay-logo.png" alt="Logo Municipalidad" class="logo">
            </a>

            <button class="menu-toggle" type="button" aria-label="Abrir menú" aria-expanded="false" aria-controls="nav-links">
                <i class="fas fa-bars"></i>
            </button>
            <div class="nav-center">
                <div class="nav-links" id="nav-links">
                    <div class="dropdown">
                        <a href="#" class="nav-item" aria-expanded="false">
                            <i class="fas fa-building"></i>
                            <span>Nosotros</span>
                        </a>
                        <div class="dropdown-content">
                            <div class="dropdown-section">
                                <a href="<?= $baseUrl ?>/alcalde/" class="dropdown-link">Alcalde y Regidores</a>
                                <a href="<?= $baseUrl ?>/mision-vision/" class="dropdown-link">Misión y Visión</a>
                                <a href="<?= $baseUrl ?>/historia/" class="dropdown-link">Historia</a>
                                <a href="<?= $baseUrl ?>/organigrama/" class="dropdown-link">Organigrama</a>
                            </div>
                        </div>
                    </div>

                    <div class="dropdown">
                        <a href="#" class="nav-item" aria-expanded="false">
                            <i class="fas fa-hands-helping"></i>
                            <span>Servicios</span>
                        </a>
                        <div class="dropdown-content">
                            <div class="dropdown-section">
                                <a href="<?= $baseUrl ?>/demuna/" class="dropdown-link">DEMUNA</a>
                                <a href="<?= $baseUrl ?>/codisec/" class="dropdown-link">CODISEC</a>
                                <a href="<?= $baseUrl ?>/desarrollo-urbano-y-rural/" class="dropdown-link">Desarrollo urbano y rural</a>
                            </div>
                        </div>
                    </div>

                    <div class="dropdown">
                        <a href="#" class="nav-item" aria-expanded="false">
                            <i class="fas fa-file-alt"></i>
                            <span>Transparencia</span>
                        </a>
                        <div class="dropdown-content">
                            <div class="dropdown-section">
                                <div class="dropdown-section-title">Documentos normativos</div>
                                <a href="<?= $baseUrl ?>/acuerdos%20de%20concejo/" class="dropdown-link">Acuerdos de Concejo</a>
                                <a href="<?= $baseUrl ?>/resoluciones-de-alcaldia/" class="dropdown-link">Resoluciones de Alcaldía</a>
                                <a href="<?= $baseUrl ?>/decreto-de-alcaldia/" class="dropdown-link">Decretos de Alcaldía</a>
                                <a href="<?= $baseUrl ?>/ordenanzas-municipales/" class="dropdown-link">Ordenanzas Municipales</a>
                                <a href="<?= $baseUrl ?>/resoluciones-de-gerencia/" class="dropdown-link">Resoluciones de Gerencia</a>
                                <a href="<?= $baseUrl ?>/directivas/" class="dropdown-link">Directivas Municipales</a>
                            </div>
                            <div class="dropdown-section">
                                <div class="dropdown-section-title">Gestión y convocatorias</div>
                                <a href="<?= $baseUrl ?>/instrumentos_gestion/" class="dropdown-link">Instrumentos de Gestión</a>
                                <a href="<?= $baseUrl ?>/convocatorias-cas/" class="dropdown-link">Convocatorias CAS</a>
                            </div>
                            <div class="dropdown-section">
                                <div class="dropdown-section-title">Otros documentos</div>
                                <a href="<?= $baseUrl ?>/otros-documentos-de-interes/" class="dropdown-link">Documentos de Interés</a>
                                <a href="<?= $baseUrl ?>/proceso%20de%20seleccion/" class="dropdown-link">Procesos de selección</a>
                                <a href="<?= $baseUrl ?>/Interaccion%20con%20el%20mercado/" class="dropdown-link">Interacción con el mercado</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <a href="<?= $baseUrl ?>/acuerdos%20de%20concejo/" class="portal-transparencia">Portal de Transparencia →</a>
        </div>
    </nav>
</div>

<script src="<?= $baseUrl ?>/assets/js/header.js" defer></script>
<?php
// index.php - Página de inicio Municipalidad de Lamay
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Municipalidad Distrital de Lamay - El encanto del valle. Servicios municipales, trámites en línea y números de emergencia.">
    <title>Municipalidad Distrital de Lamay</title>

    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/home.css">
    <link rel="icon" type="image/png" href="/assets/images/lamay-logo.png">



</head>
<body>

    <!-- ============================================
         HEADER Y HERO DINÁMICO
         ============================================ -->
    <div class="muni-header">
        <!-- Barra superior dinámica -->
        <div class="top-bar">
            <div class="top-bar-content">
                <div class="contact-info">
                    <span class="contact-item">
                        <i class="fas fa-phone"></i>
                        <a id="header-phone" href="#" style="color: inherit; text-decoration: none;">--</a>
                    </span>
                    <span class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <a id="header-email" href="#">--</a>
                    </span>
                    <span class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span id="header-address">--</span>
                    </span>
                </div>
                <div class="social-icons">
                    <a id="social-facebook" href="#" target="_blank" rel="noopener" aria-label="Facebook" style="display: none;"><i class="fab fa-facebook-f"></i></a>
                    <a id="social-whatsapp" href="#" target="_blank" rel="noopener" aria-label="WhatsApp" style="display: none;"><i class="fab fa-whatsapp"></i></a>
                    <a id="social-tiktok" href="#" target="_blank" rel="noopener" aria-label="TikTok" style="display: none;"><i class="fab fa-tiktok"></i></a>
                    <a id="social-youtube" href="#" target="_blank" rel="noopener" aria-label="YouTube" style="display: none;"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>

        <!-- Sección Hero con Video Dinámico -->
        <div class="hero-section">
            <div class="video-background">
                <video id="hero-video-player" autoplay loop muted playsinline style="display: none;">
                    <source id="hero-video-source" src="" type="video/mp4">
                    Tu navegador no soporta la reproducción de video.
                </video>
                <div class="video-overlay-header"></div>
            </div>

            <!-- Navbar principal -->
            <nav class="nav-bar" id="main-navbar">
                <div class="nav-content">
                    <a href="" class="logo-link" aria-label="Inicio">
                        <img id="site-logo" src="/assets/images/lamay-logo.png" alt="Logo Municipalidad" class="logo">
                    </a>

                    <button class="menu-toggle" id="menu-toggle-btn" aria-label="Abrir menú">
                        <i class="fas fa-bars"></i>
                    </button>

                    <div class="nav-center">
                        <div class="nav-links" id="nav-links">
                            <div class="dropdown">
                                <a href="#" class="nav-item">
                                    <i class="fas fa-building"></i>
                                    <span>Nosotros</span>
                                </a>
                                <div class="dropdown-content">
                                    <div class="dropdown-section">
                                        <a href="alcalde/" class="dropdown-link">Alcalde y Regidores</a>
                                        <a href="mision-vision/" class="dropdown-link">Misión, Visión</a>
                                        <a href="historia/" class="dropdown-link">Historia</a>
                                        <a href="organigrama/" class="dropdown-link">Organigrama</a>
                                    </div>
                                </div>
                            </div>

                            <div class="dropdown">
                                <a href="#" class="nav-item">
                                    <i class="fas fa-hands-helping"></i>
                                    <span>Servicios</span>
                                </a>
                                <div class="dropdown-content">
                                    <div class="dropdown-section">
                                        <a href="demuna/" class="dropdown-link">Demuna</a>
                                        <a href="codisec/" class="dropdown-link">Codisec</a>
                                        <a href="desarrollo-urbano-y-rural/" class="dropdown-link">Ordenamiento Territorial</a>
                                    </div>
                                </div>
                            </div>

                            <div class="dropdown">
                                <a href="#" class="nav-item">
                                    <i class="fas fa-file-alt"></i>
                                    <span>Transparencia</span>
                                </a>
                                <div class="dropdown-content">
                                    <div class="dropdown-section">
                                        <div class="dropdown-section-title">Documentos Normativos</div>
                                        <a href="acuerdos%20de%20concejo/" class="dropdown-link">Acuerdos de Concejo</a>
                                        <a href="resoluciones-de-alcaldia/" class="dropdown-link">Resoluciones de Alcaldía</a>
                                        <a href="decreto-de-alcaldia/" class="dropdown-link">Decretos de Alcaldía</a>
                                        <a href="ordenanzas-municipales/" class="dropdown-link">Ordenanzas Municipales</a>
                                        <a href="resoluciones-de-gerencia/" class="dropdown-link">Resoluciones de Gerencia</a>
                                        <a href="directivas/" class="dropdown-link">Directivas Municipales</a>
                                    </div>
                                    <div class="dropdown-section">
                                        <div class="dropdown-section-title">Organización</div>
                                        <a href="instrumentos_gestion/" class="dropdown-link">Instrumentos de Gestión</a>
                                    </div>
                                    <div class="dropdown-section">
                                        <div class="dropdown-section-title">Procedimientos</div>
                                        <a href="convocatorias-cas/" class="dropdown-link">Convocatorias CAS</a>
                                    </div>
                                    <div class="dropdown-section">
                                        <div class="dropdown-section-title">Otros</div>
                                        <a href="otros-documentos-de-interes/" class="dropdown-link">Documentos de Interés</a>
                                        <a href="proceso%20de%20seleccion/" class="dropdown-link">Procesos de selección</a>
                                        <a href="Interaccion%20con%20el%20mercado/" class="dropdown-link">Interacción con el mercado</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <a href="https://transparencia.gob.pe/enlaces/pte_transparencia_enlaces.aspx?id_entidad=10972" target="_blank" rel="noopener" class="portal-transparencia">Portal De Transparencia →</a>
                </div>
            </nav>

            <!-- Textos del Hero -->
            <div class="hero-content">
                <div class="hero-text">
                    <h1 id="hero-title" class="hero-title">LAMAY</h1>
                    <h2 id="rotating-text" class="hero-subtitle">El encanto del valle</h2>
                    <a id="hero-action-btn" href="#" target="_blank" rel="noopener" class="hero-button" style="display: none;">
                        Ver más <i class="fas fa-play-circle" style="margin-left: 6px;"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================
         CONTENIDO PRINCIPAL
         ============================================ -->
    <main class="main-sections">

        <!-- Sección de Servicios Municipales -->
        <section class="section-container" id="servicios">
            <h2 class="section-title">Servicios Municipales</h2>
            <p class="section-subtitle">Acceda a los principales servicios digitales que ofrece la Municipalidad de Lamay</p>

            <div class="cards-grid">
                <article class="service-card">
                    <div class="service-card-header">
                        <div class="service-icon"><i class="fas fa-user-tie"></i></div>
                        <h3 class="service-title">Alcalde y Regidores</h3>
                    </div>
                    <div class="service-card-body">
                        <p class="service-description">Conozca a nuestras autoridades municipales, sus funciones y responsabilidades en la gestión de la municipalidad.</p>
                        <div class="service-footer">
                            <span class="service-badge">Autoridades</span>
                            <a href="/alcalde/" class="service-link">Acceder <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </article>

                <article class="service-card">
                    <div class="service-card-header">
                        <div class="service-icon"><i class="fas fa-file-alt"></i></div>
                        <h3 class="service-title">Mesa de Partes Virtual</h3>
                    </div>
                    <div class="service-card-body">
                        <p class="service-description">Presente sus documentos, solicitudes y trámites de forma digital sin necesidad de acudir presencialmente.</p>
                        <div class="service-footer">
                            <span class="service-badge">Trámites</span>
                            <a href="https://facilita.gob.pe/t/3326" target="_blank" rel="noopener" class="service-link">Acceder <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </article>

                <article class="service-card">
                    <div class="service-card-header">
                        <div class="service-icon"><i class="fas fa-landmark"></i></div>
                        <h3 class="service-title">Portal Web Gob.pe</h3>
                    </div>
                    <div class="service-card-body">
                        <p class="service-description">Acceda al portal oficial del estado peruano con información actualizada sobre trámites y servicios.</p>
                        <div class="service-footer">
                            <span class="service-badge">Oficial</span>
                            <a href="https://www.gob.pe/munilamay" target="_blank" rel="noopener" class="service-link">Acceder <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </article>

                <article class="service-card">
                    <div class="service-card-header">
                        <div class="service-icon"><i class="fas fa-envelope"></i></div>
                        <h3 class="service-title">Correo Institucional</h3>
                    </div>
                    <div class="service-card-body">
                        <p class="service-description">Sistema de correo electrónico oficial para comunicación interna de la municipalidad.</p>
                        <div class="service-footer">
                            <span class="service-badge">Interno</span>
                            <a href="https://munilamay.gob.pe:2096/" target="_blank" rel="noopener" class="service-link">Acceder <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </article>

                <article class="service-card">
                    <div class="service-card-header">
                        <div class="service-icon"><i class="fas fa-comment-alt"></i></div>
                        <h3 class="service-title">Libro de Reclamaciones</h3>
                    </div>
                    <div class="service-card-body">
                        <p class="service-description">Registre sus quejas, reclamos o sugerencias para mejorar nuestros servicios municipales.</p>
                        <div class="service-footer">
                            <span class="service-badge">Ciudadano</span>
                            <a href="https://facilita.gob.pe/t/3383" target="_blank" rel="noopener" class="service-link">Acceder <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </article>

                <article class="service-card">
                    <div class="service-card-header">
                        <div class="service-icon"><i class="fas fa-eye"></i></div>
                        <h3 class="service-title">Portal de Transparencia</h3>
                    </div>
                    <div class="service-card-body">
                        <p class="service-description">Acceda a información pública sobre gestión municipal, presupuestos y rendición de cuentas.</p>
                        <div class="service-footer">
                            <span class="service-badge">Transparencia</span>
                            <a href="https://transparencia.gob.pe/enlaces/pte_transparencia_enlaces.aspx?id_entidad=10972" target="_blank" rel="noopener" class="service-link">Acceder <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </article>
            </div>
        </section>

        <div class="section-divider"></div>

        <!-- Sección de Números de Emergencia -->
        <section class="section-container" id="emergencias">
            <h2 class="section-title">Números de Emergencia</h2>
            <p class="section-subtitle">Mantenga estos números a mano para situaciones de emergencia en el distrito de Lamay</p>

            <div class="cards-grid">
                <div class="emergency-glass-card pnp">
                    <div class="emergency-glass-header">
                        <div class="emergency-glass-icon"><i class="fas fa-user-shield"></i></div>
                        <div class="emergency-glass-title">
                            <h3>PAR (PNP)</h3>
                            <p>Policía Nacional del Perú</p>
                        </div>
                    </div>
                    <div class="emergency-glass-footer">
                        <a id="emergencia-pnp" href="#">--</a>
                    </div>
                </div>

                <div class="emergency-glass-card serenazgo">
                    <div class="emergency-glass-header">
                        <div class="emergency-glass-icon"><i class="fas fa-shield-alt"></i></div>
                        <div class="emergency-glass-title">
                            <h3>Serenazgo</h3>
                            <p>Seguridad Municipal</p>
                        </div>
                    </div>
                    <div class="emergency-glass-footer">
                        <a id="emergencia-serenazgo" href="#">--</a>
                    </div>
                </div>

                <div class="emergency-glass-card defensa-civil">
                    <div class="emergency-glass-header">
                        <div class="emergency-glass-icon"><i class="fas fa-house-damage"></i></div>
                        <div class="emergency-glass-title">
                            <h3>Defensa Civil</h3>
                            <p>Prevención de Desastres</p>
                        </div>
                    </div>
                    <div class="emergency-glass-footer">
                        <a id="emergencia-defensa-civil" href="#">--</a>
                    </div>
                </div>
            </div>
        </section>

    </main>


    @include('partials.footer')

    <script src="/assets/js/index.js"></script>

</body>
</html>
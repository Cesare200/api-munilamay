<?php
require dirname(__DIR__).'/includes/bootstrap.php';
$pageTitle = 'DEMUNA';
$pageDescription = 'Defensoría Municipal del Niño y del Adolescente de Lamay.';
$pageStyles = ['demuna.css'];
require dirname(__DIR__).'/includes/header.php';
?>
<main class="page-main">
  <div class="container">
    <nav class="breadcrumbs" aria-label="Ruta de navegación">
      <a href="<?= $baseUrl ?>/">Inicio</a>
      <span aria-hidden="true">/</span>
      <span>Servicios</span>
      <span aria-hidden="true">/</span>
      <span aria-current="page">DEMUNA</span>
    </nav>

    <div class="demuna-component" aria-label="Defensoría Municipal del Niño, Niña y Adolescente">
      <div class="demuna-icons-container" aria-hidden="true">
        <div class="demuna-icon-item">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
            <circle cx="9" cy="7" r="4"></circle>
            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
          </svg>
        </div>
        <div class="demuna-icon-item">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
            <polyline points="14 2 14 8 20 8"></polyline>
            <line x1="16" y1="13" x2="8" y2="13"></line>
            <line x1="16" y1="17" x2="8" y2="17"></line>
            <polyline points="10 9 9 9 8 9"></polyline>
          </svg>
        </div>
        <div class="demuna-icon-item">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
          </svg>
        </div>
        <div class="demuna-icon-item">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="12" cy="12" r="10"></circle>
            <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
            <line x1="12" y1="17" x2="12.01" y2="17"></line>
          </svg>
        </div>
        <div class="demuna-icon-item">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
          </svg>
        </div>
      </div>

      <div class="demuna-header">
        <h1 class="demuna-main-title">DEMUNA</h1>
        <h2 class="demuna-subtitle">Defensoría Municipal del Niño, Niña y Adolescente</h2>
      </div>

      <div class="demuna-description">
        <p class="demuna-text">
          Protegemos y defendemos los derechos de los niños, niñas y adolescentes,
          brindando servicios gratuitos de conciliación, orientación y apoyo integral.
        </p>
      </div>

      <div class="demuna-contact-section">
        <div class="demuna-contact-item">
          <div class="demuna-contact-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
              <circle cx="12" cy="10" r="3"></circle>
            </svg>
          </div>
          <div class="demuna-contact-info">
            <div>DEMUNA - Lamay</div>
            <div>Municipalidad Distrital de Lamay</div>
          </div>
        </div>

        <div class="demuna-contact-item">
          <div class="demuna-contact-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"></circle>
              <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
          </div>
          <div class="demuna-contact-info">
            <div>Horario de Atención</div>
            <div>8:00 a.m. - 4:30 p.m.</div>
          </div>
        </div>

        <div class="demuna-contact-item">
          <div class="demuna-contact-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
            </svg>
          </div>
          <div class="demuna-contact-info">
            <div>Teléfono</div>
            <div>000 000 000</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<?php require dirname(__DIR__).'/includes/footer.php'; ?>

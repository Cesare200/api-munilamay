<?php
require dirname(__DIR__).'/includes/bootstrap.php';
$pageTitle = 'CODISEC';
$pageDescription = 'Comité Distrital de Seguridad Ciudadana de Lamay.';
$pageStyles = ['codisec.css'];
require dirname(__DIR__).'/includes/header.php';
?>
<main class="page-main codisec-page">
  <div class="container">
    <nav class="breadcrumbs" aria-label="Ruta de navegación">
      <a href="<?= $baseUrl ?>/">Inicio</a>
      <span aria-hidden="true">/</span>
      <span>Servicios</span>
      <span aria-hidden="true">/</span>
      <span aria-current="page">CODISEC</span>
    </nav>

    <div class="codisec-module" aria-label="Módulo CODISEC">
      <header class="codisec-header">
        <div class="codisec-logo-wrap" aria-hidden="true">
          <div class="codisec-logo"><i class="fas fa-shield-alt"></i></div>
        </div>
        <div class="codisec-header-text">
          <h1 class="codisec-title">CODISEC</h1>
          <p class="codisec-subtitle">Comité Distrital de Seguridad Ciudadana</p>
          <div class="codisec-meta">
            <span class="codisec-chip"><i class="fas fa-landmark"></i>Lamay</span>
            <span class="codisec-chip"><i class="fas fa-balance-scale"></i>Ley N° 27933</span>
            <span class="codisec-chip"><i class="fas fa-users"></i>Coordinación interinstitucional</span>
          </div>
        </div>
      </header>

      <div class="codisec-tabs">
        <div class="codisec-tab-list" role="tablist" aria-label="Secciones del CODISEC">
          <button class="codisec-tab-btn active" type="button" role="tab" aria-selected="true" data-target="tab-objetivos">
            <i class="fas fa-bullseye"></i>Objetivos
          </button>
          <button class="codisec-tab-btn" type="button" role="tab" aria-selected="false" data-target="tab-comision">
            <i class="fas fa-user-shield"></i>Comisión
          </button>
          <button class="codisec-tab-btn" type="button" role="tab" aria-selected="false" data-target="tab-agenda">
            <i class="fas fa-calendar-check"></i>Agenda
          </button>
          <button class="codisec-tab-btn" type="button" role="tab" aria-selected="false" data-target="tab-normativa">
            <i class="fas fa-file-contract"></i>Normativa
          </button>
          <button class="codisec-tab-btn" type="button" role="tab" aria-selected="false" data-target="tab-documentos">
            <i class="fas fa-folder-open"></i>Documentos
          </button>
        </div>

        <div id="tab-objetivos" class="codisec-tab-panel active" role="tabpanel">
          <div class="section-heading">
            <h2>Objetivos institucionales</h2>
            <small>CODISEC</small>
          </div>

          <div class="codisec-fact-grid">
            <div class="codisec-fact">
              <div class="codisec-fact-icon"><i class="fas fa-city"></i></div>
              <strong>01</strong>
              <span>Prevención</span>
            </div>
            <div class="codisec-fact">
              <div class="codisec-fact-icon"><i class="fas fa-people-arrows"></i></div>
              <strong>02</strong>
              <span>Coordinación</span>
            </div>
            <div class="codisec-fact">
              <div class="codisec-fact-icon"><i class="fas fa-shield-virus"></i></div>
              <strong>03</strong>
              <span>Seguridad</span>
            </div>
            <div class="codisec-fact">
              <div class="codisec-fact-icon"><i class="fas fa-chart-line"></i></div>
              <strong>04</strong>
              <span>Monitoreo</span>
            </div>
          </div>

          <div class="codisec-accordion" style="margin-top: 16px;">
            <div class="codisec-accordion-item active">
              <button class="codisec-accordion-header" type="button" aria-expanded="true">
                <span><i class="fas fa-bullseye"></i> ¿Qué es el CODISEC?</span>
                <i class="fas fa-chevron-down toggle-icon"></i>
              </button>
              <div class="codisec-accordion-body">
                <p>El Comité Distrital de Seguridad Ciudadana es la instancia de articulación y coordinación entre las autoridades, instituciones y actores de la sociedad civil con la finalidad de formular, ejecutar y supervisar políticas, planes, programas y acciones orientadas a prevenir la violencia, el delito y los riesgos sociales en el distrito.</p>
              </div>
            </div>
            <div class="codisec-accordion-item">
              <button class="codisec-accordion-header" type="button" aria-expanded="false">
                <span><i class="fas fa-check-circle"></i> Objetivos prioritarios</span>
                <i class="fas fa-chevron-down toggle-icon"></i>
              </button>
              <div class="codisec-accordion-body">
                <ul>
                  <li>Fortalecer la coordinación entre instituciones públicas y privadas.</li>
                  <li>Promover estrategias de prevención y atención de la inseguridad ciudadana.</li>
                  <li>Mejorar la respuesta ante emergencias y hechos de violencia.</li>
                  <li>Generar información y diagnósticos para la toma de decisiones.</li>
                </ul>
              </div>
            </div>
          </div>
        </div>

        <div id="tab-comision" class="codisec-tab-panel" role="tabpanel" hidden>
          <div class="section-heading">
            <h2>Integrantes de la comisión</h2>
            <small>Participación multisectorial</small>
          </div>

          <ul class="codisec-list">
            <li><i class="fas fa-user-tie"></i><span>Alcaldía Distrital como autoridad local responsable de la coordinación del comité.</span></li>
            <li><i class="fas fa-user-shield"></i><span>Representantes de la Policía Nacional del Perú y otras entidades de seguridad.</span></li>
            <li><i class="fas fa-hospital-user"></i><span>Representantes del sistema de salud y atención de emergencias.</span></li>
            <li><i class="fas fa-school"></i><span>Instituciones educativas y organizaciones juveniles vinculadas al entorno comunitario.</span></li>
            <li><i class="fas fa-people-group"></i><span>Organizaciones vecinales y actores de la comunidad organizada.</span></li>
          </ul>
        </div>

        <div id="tab-agenda" class="codisec-tab-panel" role="tabpanel" hidden>
          <div class="section-heading">
            <h2>Agenda y actividades</h2>
            <small>Sesiones y seguimiento</small>
          </div>

          <div class="codisec-accordion">
            <div class="codisec-accordion-item active">
              <button class="codisec-accordion-header" type="button" aria-expanded="true">
                <span><i class="fas fa-calendar-alt"></i> Agenda ordinaria</span>
                <i class="fas fa-chevron-down toggle-icon"></i>
              </button>
              <div class="codisec-accordion-body">
                <ul>
                  <li>Revisión de indicadores de seguridad ciudadana.</li>
                  <li>Monitoreo de actividades preventivas y de respuesta.</li>
                  <li>Coordinación con autoridades locales y sectoriales.</li>
                  <li>Seguimiento de compromisos y metas del comité.</li>
                </ul>
              </div>
            </div>
            <div class="codisec-accordion-item">
              <button class="codisec-accordion-header" type="button" aria-expanded="false">
                <span><i class="fas fa-clipboard-list"></i> Actividades de prevención</span>
                <i class="fas fa-chevron-down toggle-icon"></i>
              </button>
              <div class="codisec-accordion-body">
                <ul>
                  <li>Campañas de sensibilización en centros poblados y espacios públicos.</li>
                  <li>Fortalecimiento de la participación vecinal.</li>
                  <li>Trabajo articulado con instituciones de educación y salud.</li>
                </ul>
              </div>
            </div>
          </div>
        </div>

        <div id="tab-normativa" class="codisec-tab-panel" role="tabpanel" hidden>
          <div class="section-heading">
            <h2>Marco normativo</h2>
            <small>Base legal</small>
          </div>

          <ul class="codisec-list">
            <li><i class="fas fa-file-lines"></i><span> Ley N° 27933, Ley de prevención de la violencia y seguridad ciudadana.</span></li>
            <li><i class="fas fa-file-lines"></i><span> Reglamento de organización y funcionamiento del CODISEC.</span></li>
            <li><i class="fas fa-file-lines"></i><span> Lineamientos distritales para la coordinación del sistema de seguridad ciudadana.</span></li>
            <li><i class="fas fa-file-lines"></i><span> Normas orientadas a la prevención y gestión de riesgos dentro del territorio.</span></li>
          </ul>
        </div>

        <div id="tab-documentos" class="codisec-tab-panel" role="tabpanel" hidden>
          <div class="section-heading">
            <h2>Documentos y publicaciones</h2>
            <small>Información disponible</small>
          </div>

          <div class="codisec-document-list">
            <div class="codisec-doc-item">
              <div>
                <strong>Actas de Comisión</strong>
                <small>Sesiones y acuerdos</small>
              </div>
              <a href="#"><i class="fas fa-download"></i> Ver</a>
            </div>
            <div class="codisec-doc-item">
              <div>
                <strong>Plan de Seguridad</strong>
                <small>Medidas y acciones</small>
              </div>
              <a href="#"><i class="fas fa-download"></i> Ver</a>
            </div>
            <div class="codisec-doc-item">
              <div>
                <strong>Resoluciones</strong>
                <small>Decisiones del comité</small>
              </div>
              <a href="#"><i class="fas fa-download"></i> Ver</a>
            </div>
            <div class="codisec-doc-item">
              <div>
                <strong>Informes</strong>
                <small>Indicadores y seguimiento</small>
              </div>
              <a href="#"><i class="fas fa-download"></i> Ver</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<script src="<?= $baseUrl ?>/assets/js/codisec.js?v=<?= filemtime(dirname(__DIR__).'/assets/js/codisec.js') ?>" defer></script>
<?php require dirname(__DIR__).'/includes/footer.php'; ?>

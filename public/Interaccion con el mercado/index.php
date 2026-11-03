<?php
require dirname(__DIR__) . '/includes/bootstrap.php';
$pageTitle = 'Interacción con el mercado';
$pageDescription = 'Consulta las esquelas de publicación para contratos menores difundidas por la Municipalidad Distrital de Lamay.';
$pageStyles = ['interaccion-mercado.css'];
require dirname(__DIR__) . '/includes/header.php';
?>
<main class="page-main interaccion-page">
  <div class="container">
    <nav class="breadcrumbs" aria-label="Ruta de navegación">
      <a href="<?= $baseUrl ?>/">Inicio</a>
      <span aria-hidden="true">/</span>
      <span>Transparencia</span>
      <span aria-hidden="true">/</span>
      <span aria-current="page">Interacción con el mercado</span>
    </nav>

    <section class="documento-institucional" aria-labelledby="interaccion-title">
      <header class="documento-header">
        <h1 id="interaccion-title"><i class="fas fa-handshake" aria-hidden="true"></i> Interacción con el mercado</h1>
        <div class="descripcion-pagina">
          <p>
            Teniendo presente que, mediante comunicado de la Dirección General de Abastecimiento del 22 de abril de 2025, se indica que el uso de la plataforma de Contratos Menores de la PLADICOP por parte de las entidades contratantes será obligatorio a partir de la fecha que determine la DGA mediante Resolución Directoral. En tanto ello ocurre, las entidades gestionan sus contratos menores mediante los medios digitales y físicos que tengan a disposición.
          </p>
          <p>
            En ese sentido, y con el objetivo de fortalecer la transparencia y la digitalización de las contrataciones públicas, la Oficina de Logística pone a disposición las esquelas de publicación correspondientes a contratos menores cuyos montos son iguales o inferiores a ocho (8) Unidades Impositivas Tributarias (UIT), en el marco de la Ley N.º 32069, Ley General de Contrataciones Públicas, y su Reglamento. Dichos documentos son difundidos y publicados con la finalidad de promover la participación abierta y electrónica de todos los proveedores del Estado.
          </p>
          <p>
            Esto se realiza conforme al numeral 49.1 del artículo 49 del Reglamento de la Ley General de Contrataciones Públicas, aprobado mediante D. S. N.º 009-2025-EF, el cual establece que, mediante la consulta al mercado, se propicia el intercambio de información de manera abierta y transparente con los proveedores.
          </p>
        </div>
      </header>

      <div class="documentos-lista" id="interaccionMercadoLista" aria-live="polite" aria-busy="true">
        <p class="documentos-loading"><i class="fas fa-spinner fa-spin" aria-hidden="true"></i> Cargando esquelas de interacción con el mercado...</p>
      </div>

      <footer class="documento-footer">
        <i class="fas fa-landmark" aria-hidden="true"></i>
        <span>Oficina de Logística — Municipalidad Distrital de Lamay</span>
      </footer>
    </section>
  </div>
</main>
<script src="<?= $baseUrl ?>/assets/js/interaccion-mercado.js?v=<?= filemtime(dirname(__DIR__) . '/assets/js/interaccion-mercado.js') ?>" defer></script>
<?php require dirname(__DIR__) . '/includes/footer.php'; ?>

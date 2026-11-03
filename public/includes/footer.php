<div class="muni-footer-container">
  <div class="muni-footer-content">
    <!-- Sección Superior -->
    <div class="muni-footer-top">
      <!-- Columna Logo y Redes -->
      <div class="muni-footer-col muni-footer-logo">
        <img id="footer-logo"
             src=""
             alt="Logo Municipalidad Distrital de Lamay"
             class="muni-footer-logo-img"
             style="display: none;"
             loading="lazy">
        <p class="muni-footer-slogan">Trabajando por el desarrollo sostenible de Lamay</p>

        <div class="muni-social-links">
          <a id="footer-social-facebook" href="#" class="muni-social-link" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
            <i class="fab fa-facebook-f"></i>
          </a>
          <a id="footer-social-youtube" href="#" class="muni-social-link" target="_blank" rel="noopener noreferrer" aria-label="YouTube" style="display: none;">
            <i class="fab fa-youtube"></i>
          </a>
          <a href="https://transparencia.gob.pe/enlaces/pte_transparencia_enlaces.aspx?id_entidad=10972" class="muni-social-link" target="_blank" rel="noopener noreferrer" aria-label="Portal de Transparencia">
            <i class="fas fa-landmark"></i>
          </a>
          <a href="https://facilita.gob.pe/t/3383" class="muni-social-link" target="_blank" rel="noopener noreferrer" aria-label="Libro de Reclamaciones">
            <i class="fas fa-book"></i>
          </a>
          <a href="https://facilita.gob.pe/t/3326" class="muni-social-link" target="_blank" rel="noopener noreferrer" aria-label="Mesa de Partes">
            <i class="fas fa-inbox"></i>
          </a>
        </div>
      </div>

      <!-- Columna Horario -->
      <div class="muni-footer-col muni-footer-hours">
        <h3 class="muni-footer-title">Horario de Atención</h3>
        <ul class="muni-footer-list">
          <li><i class="far fa-clock"></i> <strong id="footer-horario-dias">Cargando...</strong></li>
          <li>Mañana: <span id="footer-horario-manana">--</span></li>
          <li>Tarde: <span id="footer-horario-tarde">--</span></li>
          <li class="muni-holiday-info"><i class="fas fa-info-circle"></i> <span id="footer-horario-feriados">--</span></li>
        </ul>

        <div class="muni-emergency-banner">
          <i class="fas fa-exclamation-triangle"></i>
          <span>Emergencias: <a id="footer-banner-emergencia" href="#">--</a></span>
        </div>
      </div>

      <!-- Columna Contacto -->
      <div class="muni-footer-col muni-footer-contact">
        <h3 class="muni-footer-title">Contacto</h3>
        <ul class="muni-footer-list">
          <li><i class="fas fa-phone-alt"></i> PAR (PNP): <a id="footer-pnp" href="#">--</a></li>
          <li><i class="fas fa-shield-alt"></i> Serenazgo: <a id="footer-serenazgo" href="#">--</a></li>
          <li><i class="fas fa-first-aid"></i> Defensa Civil: <a id="footer-defensa-civil" href="#">--</a></li>
          <li><i class="fas fa-envelope"></i> <a id="footer-email" href="#">--</a></li>
          <li><i class="fas fa-globe"></i> <a href="https://munilamay.gob.pe:2096/" target="_blank" rel="noopener noreferrer">Webmail Institucional</a></li>
        </ul>
      </div>

      <!-- Columna Enlaces Rápidos -->
      <div class="muni-footer-col muni-footer-quicklinks">
        <h3 class="muni-footer-title">Enlaces Rápidos</h3>
        <ul class="muni-footer-list">
          <li><i class="fas fa-file-alt"></i> <a href="/transparencia/">Transparencia</a></li>
          <li><i class="fas fa-bullhorn"></i> <a href="/convocatorias-cas/">Convocatorias</a></li>
          <li><i class="fas fa-gavel"></i> <a href="/ordenanzas-municipales/">Ordenanzas Municipales</a></li>
          <li><i class="fas fa-shield-alt"></i> <a href="https://munilamay.gob.pe/wp-content/uploads/2025/06/POLITICA-DE-PRIVACIDAD.pdf" target="_blank" rel="noopener noreferrer">Política de Privacidad</a></li>
          <li><i class="fas fa-book"></i> <a href="https://facilita.gob.pe/t/3383" target="_blank" rel="noopener noreferrer">Libro de Reclamaciones</a></li>
        </ul>
      </div>
    </div>

    <!-- Sección Media -->
    <div class="muni-footer-middle">
      <div class="muni-address-container">
        <div class="muni-address">
          <i class="fas fa-map-marker-alt"></i>
          <span id="footer-address-text">Cargando dirección...</span>
        </div>

        <div class="muni-map-link">
          <a id="footer-map-link" href="#" target="_blank" rel="noopener noreferrer">
            <i class="fas fa-map"></i> Ver en Google Maps
          </a>
        </div>
      </div>
    </div>

    <!-- Sección Inferior -->
    <div class="muni-footer-bottom">
      <div class="muni-copyright">
        <p>&copy; <span class="muni-current-year"></span> Municipalidad Distrital de Lamay. Todos los derechos reservados.</p>
        <p class="muni-credits">
          Sitio web desarrollado por
          <a href="https://www.facebook.com/profile.php?id=61566920405089" target="_blank" rel="noopener noreferrer">JC IT Solutions</a>
        </p>
      </div>
    </div>
  </div>
</div>

<style>
:root {
  --muni-green-dark: #063B00;
  --muni-green-mid: #266210;
  --muni-yellow: #E1E100;
  --muni-bg-dark: #1f2124;
}

.muni-footer-container * {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.muni-footer-container {
  font-family: 'Segoe UI', Roboto, system-ui, sans-serif;
  background: var(--muni-bg-dark);
  color: #fff;
  line-height: 1.6;
  padding: 40px 0 0;
  width: 100%;
}

.muni-footer-content {
  max-width: 1300px;
  margin: 0 auto;
  padding: 0 20px;
}

.muni-footer-top {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 30px;
  padding-bottom: 20px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
  margin-bottom: 15px;
}

.muni-footer-col {
  padding: 0 10px;
}

.muni-footer-logo-img {
  max-width: 180px;
  height: auto;
  margin-bottom: 15px;
}

.muni-footer-slogan {
  color: rgba(255, 255, 255, 0.8);
  font-style: italic;
  margin-bottom: 20px;
  font-size: 14px;
}

.muni-social-links {
  display: flex;
  gap: 12px;
  margin-top: 15px;
}

.muni-social-link {
  color: #fff;
  background: var(--muni-green-mid);
  width: 36px;
  height: 36px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: transform 0.2s ease, background 0.2s ease;
}

.muni-social-link:hover {
  background: var(--muni-green-dark);
  transform: translateY(-3px);
}

.muni-footer-title {
  color: var(--muni-yellow);
  font-size: 1.1rem;
  margin-bottom: 15px;
  padding-bottom: 8px;
  border-bottom: 2px solid var(--muni-yellow);
  font-weight: 600;
  text-transform: uppercase;
}

.muni-footer-list {
  list-style: none;
}

.muni-footer-list li {
  margin-bottom: 10px;
  display: flex;
  align-items: center;
  gap: 10px;
}

.muni-footer-list a {
  color: rgba(255, 255, 255, 0.9);
  text-decoration: none;
  transition: color 0.2s ease;
}

.muni-footer-list a:hover {
  color: var(--muni-yellow);
  text-decoration: underline;
}

.muni-footer-list i {
  color: var(--muni-yellow);
  width: 20px;
  text-align: center;
}

.muni-holiday-info {
  color: rgba(255, 255, 255, 0.7);
  font-size: 13px;
  margin-top: 10px;
  padding-top: 10px;
  border-top: 1px dashed rgba(255, 255, 255, 0.2);
}

.muni-emergency-banner {
  background: rgba(144, 184, 0, 0.15);
  border-left: 3px solid #90B800;
  padding: 10px;
  margin-top: 15px;
  border-radius: 0 4px 4px 0;
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 14px;
}

.muni-emergency-banner i {
  color: #90B800;
}

.muni-emergency-banner a {
  color: #fff;
  font-weight: bold;
  text-decoration: none;
}

.muni-footer-middle {
  padding: 15px 0;
  margin-bottom: 15px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.muni-address-container {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 15px;
}

.muni-address {
  display: flex;
  align-items: center;
  gap: 10px;
  color: rgba(255, 255, 255, 0.9);
}

.muni-address i {
  color: var(--muni-yellow);
}

.muni-map-link a {
  color: var(--muni-yellow);
  text-decoration: none;
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 6px 12px;
  border: 1px solid var(--muni-yellow);
  border-radius: 4px;
  transition: all 0.2s ease;
}

.muni-map-link a:hover {
  background: var(--muni-yellow);
  color: var(--muni-green-dark);
}

.muni-footer-bottom {
  text-align: center;
  padding-bottom: 20px;
}

.muni-copyright {
  color: rgba(255, 255, 255, 0.6);
  font-size: 13px;
}

.muni-credits {
  font-size: 12px;
  margin-top: 5px;
}

.muni-credits a {
  color: var(--muni-yellow);
  text-decoration: none;
}

.muni-credits a:hover {
  text-decoration: underline;
}

@media (max-width: 900px) {
  .muni-footer-top {
    grid-template-columns: 1fr 1fr;
  }
  .muni-footer-logo {
    grid-column: span 2;
    text-align: center;
  }
  .muni-social-links {
    justify-content: center;
  }
}

@media (max-width: 600px) {
  .muni-footer-top {
    grid-template-columns: 1fr;
  }
  .muni-footer-logo {
    grid-column: span 1;
  }
  .muni-address-container {
    flex-direction: column;
    align-items: flex-start;
  }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const currentYearEl = document.querySelector('.muni-current-year');
    if (currentYearEl) currentYearEl.textContent = new Date().getFullYear();

    const obtenerDatos = window.obtenerConfiguracionMuni
        ? window.obtenerConfiguracionMuni()
        : fetch("/api/configuracion-institucional")
            .then(response => {
                if (!response.ok) throw new Error("HTTP " + response.status);
                return response.json();
            })
            .then(response => {
                if (response.status === 'success' && response.data) return response.data;
                throw new Error("Respuesta inválida de la API");
            });

    obtenerDatos.then(data => {
        if (!data) return;

        const logo = document.getElementById('footer-logo');
        if (logo && data.logo_url) {
            logo.src = data.logo_url;
            logo.style.display = 'block';
        }

        const fb = document.getElementById('footer-social-facebook');
        if (fb && data.facebook_url) fb.href = data.facebook_url;

        const yt = document.getElementById('footer-social-youtube');
        if (yt && data.youtube_url) {
            yt.href = data.youtube_url;
            yt.style.display = 'flex';
        }

        const dias = document.getElementById('footer-horario-dias');
        if (dias && data.horario_dias) dias.textContent = data.horario_dias;

        const manana = document.getElementById('footer-horario-manana');
        if (manana && data.horario_manana) manana.textContent = data.horario_manana;

        const tarde = document.getElementById('footer-horario-tarde');
        if (tarde && data.horario_tarde) tarde.textContent = data.horario_tarde;

        const feriados = document.getElementById('footer-horario-feriados');
        if (feriados && data.horario_feriados) feriados.textContent = data.horario_feriados;

        const actualizarTelefono = (elementId, value) => {
            const element = document.getElementById(elementId);
            if (!element || !value) return;
            element.textContent = value;
            element.href = 'tel:' + String(value).replace(/\s+/g, '');
        };

        actualizarTelefono('footer-banner-emergencia', data.emergencia_serenazgo);
        actualizarTelefono('footer-pnp', data.emergencia_pnp);
        actualizarTelefono('footer-serenazgo', data.emergencia_serenazgo);
        actualizarTelefono('footer-defensa-civil', data.emergencia_defensa_civil);

        const email = document.getElementById('footer-email');
        if (email && data.email_principal) {
            email.textContent = data.email_principal;
            email.href = 'mailto:' + data.email_principal;
        }

        const addr = document.getElementById('footer-address-text');
        if (addr && data.direccion_completa) addr.textContent = data.direccion_completa;

        const map = document.getElementById('footer-map-link');
        if (map && data.google_maps_url) map.href = data.google_maps_url;
    }).catch(err => console.warn("Error en Footer:", err));
});
</script>
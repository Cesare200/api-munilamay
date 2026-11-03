(function () {
    'use strict';

    const API_URL = '/api/directivas';
    const lista = document.getElementById('directivasLista');

    if (!lista) {
        console.error('No se pudo inicializar Directivas Municipales: falta el contenedor de resultados.');
        return;
    }

    function textValue(value) {
        return typeof value === 'string' || typeof value === 'number' ? String(value).trim() : '';
    }

    function formatDate(value) {
        const date = textValue(value);
        const match = date.match(/^(\d{4})-(\d{2})-(\d{2})/);
        return match ? `${match[3]}/${match[2]}/${match[1]}` : date || '—';
    }

    function parseUrl(value) {
        const rawUrl = textValue(value);
        if (!rawUrl || rawUrl.startsWith('//')) return null;

        try {
            const url = new URL(rawUrl, window.location.origin);
            return ['http:', 'https:'].includes(url.protocol) ? url.href : null;
        } catch {
            return null;
        }
    }

    function makeIcon(className) {
        const icon = document.createElement('i');
        icon.className = `fas ${className}`;
        icon.setAttribute('aria-hidden', 'true');
        return icon;
    }

    function renderEmpty(message, className, iconClass) {
        lista.replaceChildren();
        lista.setAttribute('aria-busy', 'false');
        const state = document.createElement('p');
        state.className = className;
        if (iconClass) state.appendChild(makeIcon(iconClass));
        state.appendChild(document.createTextNode(message));
        lista.appendChild(state);
    }

    function renderCard(directiva) {
        const card = document.createElement('article');
        card.className = 'directiva-card';

        const icon = document.createElement('div');
        icon.className = 'directiva-icon';
        icon.appendChild(makeIcon('fa-file-pdf'));

        const info = document.createElement('div');
        info.className = 'directiva-info';

        const title = document.createElement('h2');
        title.className = 'directiva-title';
        title.textContent = textValue(directiva.titulo) || 'DIRECTIVA MUNICIPAL';

        const description = document.createElement('p');
        description.className = 'directiva-description';
        description.textContent = textValue(directiva.descripcion) || 'Sin descripción disponible.';

        const meta = document.createElement('div');
        meta.className = 'directiva-meta';

        const published = document.createElement('div');
        published.className = 'meta-item';
        published.append(makeIcon('fa-calendar-alt'), document.createTextNode(`Publicado: ${formatDate(directiva.fecha)}`));
        meta.appendChild(published);

        const pdfUrl = parseUrl(directiva.pdf_url || directiva.pdf);
        if (pdfUrl) {
            const downloadMeta = document.createElement('div');
            downloadMeta.className = 'meta-item';
            const download = document.createElement('a');
            download.className = 'download-link';
            download.href = pdfUrl;
            download.target = '_blank';
            download.rel = 'noopener noreferrer';
            download.append(makeIcon('fa-download'), document.createTextNode(' Descargar PDF'));
            downloadMeta.appendChild(download);
            meta.appendChild(downloadMeta);
        } else {
            const unavailable = document.createElement('span');
            unavailable.className = 'meta-item';
            unavailable.textContent = 'Documento no disponible';
            meta.appendChild(unavailable);
        }

        info.append(title, description, meta);
        card.append(icon, info);
        return card;
    }

    fetch(API_URL, { cache: 'no-store' })
        .then(response => {
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            return response.json();
        })
        .then(response => {
            const items = Array.isArray(response)
                ? response
                : Array.isArray(response.data)
                    ? response.data
                    : Array.isArray(response.data?.data)
                        ? response.data.data
                        : null;
            if (items === null) throw new Error('Respuesta inválida de la API');

            if (items.length === 0) {
                renderEmpty('No hay directivas municipales disponibles en este momento.', 'directivas-empty', 'fa-folder-open');
                return;
            }

            const fragment = document.createDocumentFragment();
            items.filter(item => item && typeof item === 'object')
                .forEach(item => fragment.appendChild(renderCard(item)));
            lista.replaceChildren(fragment);
            lista.setAttribute('aria-busy', 'false');
        })
        .catch(error => {
            console.error('Error cargando directivas desde la API:', error);
            renderEmpty('No se pudo conectar con el servidor para cargar las directivas.', 'directivas-error', 'fa-exclamation-triangle');
        });
})();

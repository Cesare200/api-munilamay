(function () {
    'use strict';

    const API_URL = '/api/documentos-interes';
    const API_ORIGIN = window.location.origin;
    const STORAGE_BASE = `${API_ORIGIN}/storage/`;
    const container = document.getElementById('otrosdocsGrid');

    if (!container) {
        console.error('No se pudo inicializar Otros Documentos: falta el contenedor de resultados.');
        return;
    }

    function textValue(value) {
        return typeof value === 'string' || typeof value === 'number' ? String(value).trim() : '';
    }

    function formatDate(value) {
        const date = textValue(value);
        const match = date.match(/^(\d{4})-(\d{2})-(\d{2})/);
        if (!match) return date || 'Fecha no especificada';
        const parsed = new Date(Number(match[1]), Number(match[2]) - 1, Number(match[3]));
        return parsed.toLocaleDateString('es-PE', { day: 'numeric', month: 'long', year: 'numeric' });
    }

    function resolvePdf(item) {
        const rawUrl = textValue(item.pdf_url) || textValue(item.pdf);
        if (!rawUrl || rawUrl === '#' || rawUrl.startsWith('//')) return null;

        try {
            let url;
            if (/^https?:\/\//i.test(rawUrl)) {
                url = new URL(rawUrl);
            } else if (rawUrl.startsWith('/storage/')) {
                url = new URL(rawUrl, API_ORIGIN);
            } else {
                url = new URL(rawUrl.replace(/^\/+/, ''), STORAGE_BASE);
            }
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

    function renderCard(documentItem) {
        const card = document.createElement('article');
        card.className = 'otrosdocs-card';

        const icon = document.createElement('div');
        icon.className = 'otrosdocs-icon';
        icon.appendChild(makeIcon('fa-file-pdf'));

        const info = document.createElement('div');
        info.className = 'otrosdocs-info';

        const title = document.createElement('h2');
        title.className = 'otrosdocs-title';
        title.textContent = textValue(documentItem.titulo) || 'DOCUMENTO INSTITUCIONAL';

        const description = document.createElement('p');
        description.className = 'otrosdocs-description';
        description.textContent = textValue(documentItem.descripcion) || 'Sin descripción disponible.';

        const meta = document.createElement('div');
        meta.className = 'otrosdocs-meta';
        const published = document.createElement('div');
        published.className = 'meta-item';
        published.append(makeIcon('fa-calendar-alt'), document.createTextNode(`Publicado: ${formatDate(documentItem.fecha)}`));
        meta.appendChild(published);

        const pdfUrl = resolvePdf(documentItem);
        if (pdfUrl) {
            const downloadMeta = document.createElement('div');
            downloadMeta.className = 'meta-item';
            const download = document.createElement('a');
            download.className = 'download-link';
            download.href = pdfUrl;
            download.target = '_blank';
            download.rel = 'noopener noreferrer';
            download.append(makeIcon('fa-file-download'), document.createTextNode(' Descargar PDF'));
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

    function renderState(message, className, iconClass) {
        container.replaceChildren();
        container.setAttribute('aria-busy', 'false');
        const state = document.createElement('p');
        state.className = className;
        if (iconClass) state.appendChild(makeIcon(iconClass));
        state.appendChild(document.createTextNode(message));
        container.appendChild(state);
    }

    fetch(API_URL, { cache: 'no-store' })
        .then(response => {
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            return response.json();
        })
        .then(response => {
            const data = response.status === 'success' ? response.data : null;
            const items = Array.isArray(response)
                ? response
                : Array.isArray(data)
                    ? data
                    : Array.isArray(data?.data)
                        ? data.data
                        : null;
            if (items === null) throw new Error('Respuesta inválida de la API');

            const validItems = items.filter(item => item && typeof item === 'object');
            if (validItems.length === 0) {
                renderState('No se encontraron documentos disponibles.', 'otrosdocs-empty', 'fa-folder-open');
                return;
            }

            const fragment = document.createDocumentFragment();
            validItems.forEach(item => fragment.appendChild(renderCard(item)));
            container.replaceChildren(fragment);
            container.setAttribute('aria-busy', 'false');
        })
        .catch(error => {
            console.error('Error cargando documentos de interés:', error);
            renderState('No se pudo conectar con el servidor para cargar los documentos.', 'otrosdocs-error', 'fa-exclamation-triangle');
        });
})();

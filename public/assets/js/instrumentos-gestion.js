(function () {
    'use strict';

    const API_URL = '/api/instrumentos-gestion';
    const API_ORIGIN = window.location.origin;
    const STORAGE_BASE = `${API_ORIGIN}/storage/`;
    const container = document.getElementById('instrumentos-container');
    const searchInput = document.getElementById('search-instrumentos');

    if (!container || !searchInput) {
        console.error('No se pudo inicializar Instrumentos de Gestión: faltan elementos requeridos en la página.');
        return;
    }

    let instruments = [];

    function textValue(value) {
        return typeof value === 'string' || typeof value === 'number' ? String(value).trim() : '';
    }

    function formatDate(value) {
        const date = textValue(value);
        const match = date.match(/^(\d{4})-(\d{2})-(\d{2})/);
        return match ? `${match[3]}/${match[2]}/${match[1]}` : date || 'No especificada';
    }

    function resolvePdf(item) {
        const rawUrl = textValue(item.pdf_url) || textValue(item.pdf);
        if (!rawUrl || rawUrl === '#') return null;
        if (rawUrl.startsWith('//')) return null;

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

    function addMetaItem(containerElement, iconClass, label, value) {
        const item = document.createElement('div');
        item.className = 'meta-item';
        item.appendChild(makeIcon(iconClass));

        const text = document.createElement('span');
        text.append(document.createTextNode(`${label}: `));
        const strong = document.createElement('strong');
        strong.textContent = value;
        text.appendChild(strong);

        item.appendChild(text);
        containerElement.appendChild(item);
    }

    function renderCard(instrument) {
        const card = document.createElement('article');
        card.className = 'documento-institucional';

        const inner = document.createElement('div');
        inner.className = 'documento-inner';

        const iconBox = document.createElement('div');
        iconBox.className = 'documento-icon-box';
        iconBox.setAttribute('aria-hidden', 'true');
        iconBox.title = 'Documento institucional';
        iconBox.appendChild(makeIcon('fa-file-pdf'));

        const info = document.createElement('div');
        info.className = 'documento-info';

        const topMeta = document.createElement('div');
        topMeta.className = 'documento-top-meta';
        const type = document.createElement('span');
        type.className = 'tag-tipo';
        type.textContent = instrument.type || 'DOC';
        topMeta.appendChild(type);

        if (instrument.year) {
            const year = document.createElement('span');
            year.className = 'tag-year';
            year.append(makeIcon('fa-calendar-alt'), document.createTextNode(` ${instrument.year}`));
            topMeta.appendChild(year);
        }

        const status = document.createElement('span');
        status.className = 'tag-status';
        status.append(makeIcon('fa-check-circle'), document.createTextNode(` ${instrument.status || 'Publicado'}`));
        topMeta.appendChild(status);

        const title = document.createElement('h2');
        title.className = 'documento-title';
        title.textContent = instrument.name
            ? `${instrument.type || 'Documento'} - ${instrument.name}`
            : `${instrument.type || 'Documento'}${instrument.year ? ` - ${instrument.year}` : ''}`;

        const description = document.createElement('p');
        description.className = 'documento-description';
        description.textContent = instrument.description || 'Documento institucional normativo de la Municipalidad Distrital de Lamay.';

        const meta = document.createElement('div');
        meta.className = 'documento-meta';
        addMetaItem(meta, 'fa-clock', 'Publicado', formatDate(instrument.date));
        addMetaItem(meta, 'fa-stamp', 'Aprobado', instrument.approvedBy || 'Norma Municipal');

        const actions = document.createElement('div');
        actions.className = 'meta-actions';
        if (instrument.pdf) {
            const download = document.createElement('a');
            download.className = 'download-link';
            download.href = instrument.pdf;
            download.target = '_blank';
            download.rel = 'noopener noreferrer';
            download.append(makeIcon('fa-download'), document.createTextNode(' Descargar PDF'));
            download.setAttribute('aria-label', `Descargar ${title.textContent}`);
            actions.appendChild(download);
        } else {
            const unavailable = document.createElement('span');
            unavailable.className = 'meta-item';
            unavailable.textContent = 'Documento no disponible';
            actions.appendChild(unavailable);
        }
        meta.appendChild(actions);

        info.append(topMeta, title, description, meta);
        inner.append(iconBox, info);

        const footer = document.createElement('div');
        footer.className = 'documento-footer';
        footer.append(makeIcon('fa-shield-alt'), document.createTextNode('Documento normativo oficial de la Municipalidad Distrital de Lamay.'));

        card.append(inner, footer);
        return card;
    }

    function renderState(message, iconClass, isError) {
        container.replaceChildren();
        container.setAttribute('aria-busy', 'false');
        const state = document.createElement('div');
        state.className = `estado-box${isError ? ' is-error' : ''}`;
        state.appendChild(makeIcon(iconClass));
        const paragraph = document.createElement('p');
        paragraph.textContent = message;
        state.appendChild(paragraph);
        if (isError) {
            const detail = document.createElement('small');
            detail.textContent = 'Por favor, intente recargar la página nuevamente más tarde.';
            state.appendChild(detail);
        }
        container.appendChild(state);
    }

    function renderInstruments(items) {
        container.replaceChildren();
        container.setAttribute('aria-busy', 'false');
        if (items.length === 0) {
            renderState('No se encontraron instrumentos de gestión que coincidan con la búsqueda.', 'fa-folder-open', false);
            return;
        }

        const fragment = document.createDocumentFragment();
        items.forEach(instrument => fragment.appendChild(renderCard(instrument)));
        container.appendChild(fragment);
    }

    function applySearch() {
        const query = searchInput.value.trim().toLocaleLowerCase('es');
        if (!query) {
            renderInstruments(instruments);
            return;
        }
        const filtered = instruments.filter(instrument => {
            const searchable = [
                instrument.type,
                instrument.name,
                instrument.year,
                instrument.description,
                instrument.approvedBy,
                instrument.status
            ].join(' ').toLocaleLowerCase('es');
            return searchable.includes(query);
        });
        renderInstruments(filtered);
    }

    searchInput.addEventListener('input', applySearch);

    fetch(API_URL, { cache: 'no-store' })
        .then(response => {
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            return response.json();
        })
        .then(response => {
            const data = response.status === 'success' ? response.data : null;
            const items = Array.isArray(data)
                ? data
                : Array.isArray(data?.data)
                    ? data.data
                    : Array.isArray(response)
                        ? response
                        : null;
            if (items === null) throw new Error('Respuesta inválida de la API');

            instruments = items
                .filter(item => item && typeof item === 'object')
                .map(item => ({
                    type: textValue(item.tipo),
                    name: textValue(item.nombre),
                    year: textValue(item.anio),
                    description: textValue(item.descripcion),
                    date: textValue(item.fecha),
                    approvedBy: textValue(item.aprobado_por),
                    status: textValue(item.status),
                    pdf: resolvePdf(item)
                }));

            if (instruments.length === 0) {
                renderState('No hay instrumentos de gestión disponibles en este momento.', 'fa-folder-open', false);
                return;
            }
            applySearch();
        })
        .catch(error => {
            console.error('Error al cargar Instrumentos de Gestión:', error);
            renderState('No se pudo cargar la lista de instrumentos en este momento.', 'fa-exclamation-triangle', true);
        });
})();

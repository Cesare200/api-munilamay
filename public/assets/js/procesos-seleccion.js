(function () {
    'use strict';

    const API_URL = '/api/procesos-seleccion';
    const API_ORIGIN = window.location.origin;
    const API_PATH = '/api/procesos-seleccion';
    const STORAGE_BASE = `${API_ORIGIN}/storage/`;
    const grid = document.getElementById('proceso-grid');
    const filters = document.getElementById('filters-container');
    const sectionTitle = document.getElementById('titulo-seccion');
    const entityName = document.getElementById('entidad-nombre');
    const modal = document.getElementById('proceso-modal');
    const modalDialog = modal?.querySelector('.proceso-modal-content');
    const modalClose = modal?.querySelector('.proceso-modal-close');
    const modalTitle = document.getElementById('modal-title');
    const modalBody = document.getElementById('modal-body');
    const modalDownload = document.getElementById('modal-download-link');

    if (!grid || !filters || !sectionTitle || !entityName || !modal || !modalDialog
        || !modalClose || !modalTitle || !modalBody || !modalDownload) {
        console.error('No se pudo inicializar Procesos de Selección: faltan elementos requeridos.');
        return;
    }

    let documents = [];
    let activeFilter = null;
    let lastFocusedElement = null;

    function textValue(value) {
        return typeof value === 'string' || typeof value === 'number' ? String(value).trim() : '';
    }

    function parseUrl(value, base = STORAGE_BASE) {
        const rawUrl = textValue(value);
        if (!rawUrl || rawUrl === '#' || rawUrl.startsWith('//')) return null;
        try {
            const url = new URL(rawUrl, base);
            return ['http:', 'https:'].includes(url.protocol) ? url.href : null;
        } catch {
            return null;
        }
    }

    function resolveDocumentUrl(item) {
        const direct = textValue(item.file_url) || textValue(item.url);
        if (direct) return parseUrl(direct, API_ORIGIN);
        const file = textValue(item.file);
        return file ? parseUrl(file.replace(/^\/+/, ''), STORAGE_BASE) : null;
    }

    function parseDate(value) {
        const date = textValue(value);
        const match = date.match(/^(\d{4})-(\d{2})-(\d{2})/);
        if (match) {
            return new Date(Number(match[1]), Number(match[2]) - 1, Number(match[3])).getTime();
        }
        const timestamp = Date.parse(date);
        return Number.isNaN(timestamp) ? 0 : timestamp;
    }

    function formatFileType(value, url) {
        const explicitType = textValue(value).toLowerCase().replace(/^\./, '');
        const extension = url ? new URL(url).pathname.split('.').pop().toLowerCase() : '';
        const type = explicitType || extension || 'pdf';
        if (type.includes('doc')) return { type, label: 'Word', icon: 'fa-file-word', preview: false };
        if (type.includes('xls')) return { type, label: 'Excel', icon: 'fa-file-excel', preview: false };
        if (type === 'pdf') return { type, label: 'PDF', icon: 'fa-file-pdf', preview: true };
        return { type, label: type.toUpperCase(), icon: 'fa-file', preview: false };
    }

    function makeIcon(className) {
        const icon = document.createElement('i');
        icon.className = `fas ${className}`;
        icon.setAttribute('aria-hidden', 'true');
        return icon;
    }

    function normalizePageUrl(value) {
        const url = parseUrl(value, API_ORIGIN);
        if (!url) throw new Error('La API devolvió un enlace de paginación inválido');
        const parsed = new URL(url);
        if (parsed.origin !== API_ORIGIN || parsed.pathname !== API_PATH) {
            throw new Error('La API devolvió un destino de paginación no permitido');
        }
        return parsed.href;
    }

    async function fetchAllDocuments() {
        const result = [];
        const visitedPages = new Set();
        let nextUrl = API_URL;

        while (nextUrl) {
            const normalizedUrl = normalizePageUrl(nextUrl);
            if (visitedPages.has(normalizedUrl)) throw new Error('La paginación de la API contiene un ciclo');
            visitedPages.add(normalizedUrl);

            const response = await fetch(normalizedUrl, { cache: 'no-store' });
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            const payload = await response.json();

            if (result.length === 0) {
                if (textValue(payload.tituloSeccion)) sectionTitle.textContent = textValue(payload.tituloSeccion);
                if (textValue(payload.entidad)) {
                    entityName.textContent = `Entidad pública que convoca el proceso de selección: ${textValue(payload.entidad)}`;
                }
            }

            const page = payload.data;
            const items = Array.isArray(page?.data)
                ? page.data
                : Array.isArray(page)
                    ? page
                    : Array.isArray(payload.documentos)
                        ? payload.documentos
                        : null;
            if (items === null) throw new Error('Respuesta inválida de la API');
            result.push(...items.filter(item => item && typeof item === 'object'));

            nextUrl = page && typeof page === 'object' && !Array.isArray(page) && page.next_page_url
                ? normalizePageUrl(page.next_page_url)
                : null;
        }

        return result;
    }

    function renderEmpty(message, iconClass, isError = false) {
        grid.replaceChildren();
        grid.setAttribute('aria-busy', 'false');
        const state = document.createElement('p');
        state.className = `proceso-empty${isError ? ' is-error' : ''}`;
        if (iconClass) state.appendChild(makeIcon(iconClass));
        state.appendChild(document.createTextNode(message));
        grid.appendChild(state);
    }

    function updateFilterButtons() {
        filters.querySelectorAll('.proceso-filter-btn').forEach(button => {
            const selected = button.dataset.filter === activeFilter;
            button.classList.toggle('active', selected);
            button.setAttribute('aria-pressed', String(selected));
        });
    }

    function renderFilters() {
        const availableFilters = [...new Set(documents.map(item => item.filter).filter(Boolean))]
            .sort((a, b) => b.localeCompare(a, undefined, { numeric: true }));

        filters.replaceChildren();
        filters.setAttribute('aria-busy', 'false');
        if (availableFilters.length === 0) {
            renderEmpty('Aún no hay procesos de selección publicados.', 'fa-folder-open');
            return;
        }

        if (!availableFilters.includes(activeFilter)) activeFilter = availableFilters[0];
        availableFilters.forEach(filter => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = `proceso-filter-btn${filter === activeFilter ? ' active' : ''}`;
            button.textContent = filter;
            button.dataset.filter = filter;
            button.setAttribute('aria-pressed', String(filter === activeFilter));
            button.addEventListener('click', () => {
                activeFilter = filter;
                updateFilterButtons();
                renderDocuments();
            });
            filters.appendChild(button);
        });
    }

    function openModal(item, trigger) {
        if (!item.url) return;
        lastFocusedElement = trigger;
        modalTitle.textContent = item.title || 'Documento del proceso';
        modalBody.replaceChildren();

        const date = document.createElement('p');
        const dateLabel = document.createElement('strong');
        dateLabel.textContent = 'Fecha: ';
        date.append(dateLabel, document.createTextNode(item.dateLabel || item.date || '—'));

        const description = document.createElement('p');
        const descriptionLabel = document.createElement('strong');
        descriptionLabel.textContent = 'Descripción: ';
        description.append(descriptionLabel, document.createTextNode(item.description || 'Sin descripción adicional.'));
        modalBody.append(date, description);

        if (item.fileFormat.preview) {
            const preview = document.createElement('div');
            preview.className = 'proceso-pdf-preview';
            const frame = document.createElement('iframe');
            frame.src = item.url;
            frame.title = `Vista previa: ${item.title || 'Documento del proceso'}`;
            frame.loading = 'lazy';
            preview.appendChild(frame);
            modalBody.appendChild(preview);
        } else {
            const notice = document.createElement('p');
            notice.textContent = `La vista previa no está disponible para archivos ${item.fileFormat.label}. Descargue el documento para consultarlo.`;
            modalBody.appendChild(notice);
        }

        modalDownload.href = item.url;
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        modalClose.focus();
    }

    function closeModal() {
        if (modal.getAttribute('aria-hidden') === 'true') return;
        modal.setAttribute('aria-hidden', 'true');
        modalBody.replaceChildren();
        document.body.style.overflow = '';
        lastFocusedElement?.focus();
    }

    function renderCard(item) {
        const card = document.createElement('article');
        card.className = 'proceso-card';

        const icon = document.createElement('div');
        icon.className = 'proceso-icon';
        icon.appendChild(makeIcon(item.fileFormat.icon));

        const info = document.createElement('div');
        info.className = 'proceso-info';

        const title = document.createElement('h2');
        title.className = 'proceso-title';
        title.textContent = item.title || 'Sin título';
        info.appendChild(title);

        if (item.description) {
            const description = document.createElement('p');
            description.className = 'proceso-description';
            description.textContent = item.description;
            info.appendChild(description);
        }

        const meta = document.createElement('div');
        meta.className = 'proceso-meta';
        const date = document.createElement('span');
        date.className = 'meta-item';
        date.append(makeIcon('fa-calendar-alt'), document.createTextNode(` ${item.dateLabel || item.date || '—'}`));
        meta.appendChild(date);

        const status = textValue(item.status);
        if (status) {
            const statusItem = document.createElement('span');
            statusItem.className = 'meta-item';
            statusItem.append(makeIcon('fa-info-circle'), document.createTextNode(` ${status}`));
            meta.appendChild(statusItem);
        }

        if (item.url) {
            const actions = document.createElement('div');
            actions.className = 'meta-actions';

            const preview = document.createElement('button');
            preview.type = 'button';
            preview.className = 'proceso-preview-btn';
            preview.append(makeIcon('fa-eye'), document.createTextNode(' Vista rápida'));
            preview.addEventListener('click', () => openModal(item, preview));

            const download = document.createElement('a');
            download.className = 'proceso-download-link';
            download.href = item.url;
            download.target = '_blank';
            download.rel = 'noopener noreferrer';
            download.append(makeIcon('fa-download'), document.createTextNode(` Descargar ${item.fileFormat.label}`));
            actions.append(preview, download);
            meta.appendChild(actions);
        } else {
            const unavailable = document.createElement('span');
            unavailable.className = 'meta-item';
            unavailable.textContent = 'Documento no disponible';
            meta.appendChild(unavailable);
        }

        info.appendChild(meta);
        card.append(icon, info);
        return card;
    }

    function renderDocuments() {
        const currentDocuments = documents
            .filter(item => item.filter === activeFilter)
            .sort((a, b) => b.timestamp - a.timestamp);

        if (currentDocuments.length === 0) {
            renderEmpty(`No hay documentos registrados para el proceso ${activeFilter || ''}.`, 'fa-folder-open');
            return;
        }

        grid.replaceChildren();
        grid.setAttribute('aria-busy', 'false');
        const fragment = document.createDocumentFragment();
        currentDocuments.forEach(item => fragment.appendChild(renderCard(item)));
        grid.appendChild(fragment);
    }

    modalClose.addEventListener('click', closeModal);
    modal.addEventListener('click', event => {
        if (event.target === modal) closeModal();
    });
    document.addEventListener('keydown', event => {
        if (event.key === 'Escape') closeModal();
    });

    fetchAllDocuments()
        .then(items => {
            documents = items.map(item => {
                const url = resolveDocumentUrl(item);
                return {
                    filter: textValue(item.filter),
                    year: textValue(item.year),
                    number: textValue(item.number),
                    date: textValue(item.date),
                    dateLabel: textValue(item.date_label),
                    title: textValue(item.title),
                    description: textValue(item.description),
                    status: textValue(item.status),
                    url,
                    timestamp: parseDate(item.date),
                    fileFormat: formatFileType(item.file_type, url)
                };
            });

            if (documents.length === 0) {
                filters.replaceChildren();
                filters.setAttribute('aria-busy', 'false');
                renderEmpty('Aún no hay procesos de selección publicados.', 'fa-folder-open');
                return;
            }

            renderFilters();
            renderDocuments();
        })
        .catch(error => {
            console.error('Error al cargar procesos de selección:', error);
            filters.replaceChildren();
            filters.setAttribute('aria-busy', 'false');
            renderEmpty('No se pudieron cargar los procesos de selección. Intente nuevamente más tarde.', 'fa-exclamation-triangle', true);
        });
})();

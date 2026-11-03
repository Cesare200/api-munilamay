(function () {
    'use strict';

    const API_URL = '/api/convocatorias-cas';
    const API_BASE_URL = `${window.location.origin}/`;
    const grid = document.getElementById('cas-grid');
    const yearFilters = document.getElementById('filtros-anios');
    const casFilters = document.getElementById('filtros-cas');
    const searchInput = document.getElementById('search-cas');
    const sortSelect = document.getElementById('sort-by');
    const modal = document.getElementById('cas-modal');
    const modalDialog = modal?.querySelector('.cas-modal-content');
    const modalClose = modal?.querySelector('.cas-modal-close');
    const modalTitle = document.getElementById('modal-title');
    const modalBody = modal?.querySelector('.cas-modal-body');
    const modalDownload = document.getElementById('modal-download-btn');

    if (!grid || !yearFilters || !casFilters || !searchInput || !sortSelect
        || !modal || !modalDialog || !modalClose || !modalTitle || !modalBody || !modalDownload) {
        console.error('No se pudo inicializar Convocatorias CAS: faltan elementos requeridos en la página.');
        return;
    }

    let processes = [];
    let activeYear = null;
    let activeCas = 'all';
    let lastFocusedElement = null;

    function textValue(value) {
        return typeof value === 'string' || typeof value === 'number' ? String(value).trim() : '';
    }

    function parseDate(value) {
        const match = textValue(value).match(/^(\d{4})-(\d{2})-(\d{2})/);
        if (match) {
            return new Date(Number(match[1]), Number(match[2]) - 1, Number(match[3])).getTime();
        }
        const timestamp = Date.parse(textValue(value));
        return Number.isNaN(timestamp) ? 0 : timestamp;
    }

    function formatDate(value) {
        const date = textValue(value);
        const match = date.match(/^(\d{4})-(\d{2})-(\d{2})/);
        return match ? `${match[3]}/${match[2]}/${match[1]}` : date || '—';
    }

    function parseCasInfo(processName) {
        const match = textValue(processName).match(/\bCAS\s*N?[°ºo]?\.?\s*(\d+)\s*-\s*(\d{4})/i);
        if (!match) return { number: null, year: null };
        return { number: Number(match[1]), year: Number(match[2]) };
    }

    function normalizeUrl(value) {
        const rawUrl = textValue(value);
        if (!rawUrl) return null;
        const markdownMatch = rawUrl.match(/^\[[^\]]*\]\((https?:\/\/[^)]+)\)$/i);
        const candidate = markdownMatch ? markdownMatch[1] : rawUrl;

        try {
            const url = new URL(candidate, API_BASE_URL);
            if (!['http:', 'https:'].includes(url.protocol)) return null;
            url.pathname = url.pathname.replace(/\/{2,}/g, '/');
            return url.href;
        } catch {
            return null;
        }
    }

    function fileFormat(url) {
        if (!url) return { label: 'Documento', icon: 'fa-file' };
        const extension = new URL(url).pathname.split('.').pop().toLowerCase();
        if (extension === 'xls' || extension === 'xlsx') return { label: 'Descargar Excel', icon: 'fa-file-excel', preview: false };
        if (extension === 'doc' || extension === 'docx') return { label: 'Descargar Word', icon: 'fa-file-word', preview: false };
        if (extension === 'pdf') return { label: 'Descargar PDF', icon: 'fa-file-pdf', preview: true };
        return { label: 'Descargar documento', icon: 'fa-file-download', preview: false };
    }

    function isNew(item) {
        if (textValue(item.status).toLowerCase() === 'nuevo' || textValue(item.badge).toLowerCase() === 'nuevo') {
            return true;
        }
        const timestamp = parseDate(item.fecha);
        if (!timestamp) return false;
        const ageDays = (new Date().setHours(0, 0, 0, 0) - new Date(timestamp).setHours(0, 0, 0, 0)) / 86400000;
        return ageDays >= 0 && ageDays <= 15;
    }

    function makeIcon(className) {
        const icon = document.createElement('i');
        icon.className = `fas ${className}`;
        icon.setAttribute('aria-hidden', 'true');
        return icon;
    }

    function makeFilterButton(label, selected, onClick) {
        const button = document.createElement('button');
        button.type = 'button';
        button.className = `cas-filter-btn${selected ? ' active' : ''}`;
        button.textContent = label;
        button.setAttribute('aria-pressed', String(selected));
        button.addEventListener('click', onClick);
        return button;
    }

    function renderYearFilters() {
        yearFilters.replaceChildren();
        const years = [...new Set(processes.map(process => process.year).filter(Boolean))]
            .sort((a, b) => b - a);
        years.forEach(year => {
            yearFilters.appendChild(makeFilterButton(`CAS ${year}`, year === activeYear, () => {
                activeYear = year;
                activeCas = 'all';
                renderFilters();
                renderResults();
            }));
        });
    }

    function renderCasFilters() {
        casFilters.replaceChildren();
        const casNumbers = [...new Set(processes
            .filter(process => process.year === activeYear && process.casNumber !== null)
            .map(process => process.casNumber))]
            .sort((a, b) => b - a);

        casFilters.appendChild(makeFilterButton('Todos', activeCas === 'all', () => {
            activeCas = 'all';
            renderCasFilters();
            renderResults();
        }));
        casNumbers.forEach(number => {
            casFilters.appendChild(makeFilterButton(`CAS ${String(number).padStart(3, '0')}`, number === activeCas, () => {
                activeCas = number;
                renderCasFilters();
                renderResults();
            }));
        });
    }

    function renderFilters() {
        renderYearFilters();
        renderCasFilters();
    }

    function titleFor(process) {
        return textValue(process.title) || 'Convocatoria CAS';
    }

    function renderCard(process) {
        const card = document.createElement('article');
        card.className = 'cas-card';

        const header = document.createElement('div');
        header.className = 'cas-card-header';
        if (process.isNew) {
            const badge = document.createElement('span');
            badge.className = 'cas-badge-new';
            badge.append(makeIcon('fa-bell'), document.createTextNode(' Nuevo'));
            header.appendChild(badge);
        }

        const processBadge = document.createElement('span');
        processBadge.className = 'cas-badge';
        processBadge.textContent = process.processName || 'Proceso CAS';
        header.appendChild(processBadge);

        const year = document.createElement('span');
        year.className = 'cas-year';
        year.textContent = process.year ? String(process.year) : '—';
        header.appendChild(year);

        const body = document.createElement('div');
        body.className = 'cas-card-body';
        const title = document.createElement('h2');
        title.className = 'cas-title';
        title.textContent = titleFor(process);
        body.appendChild(title);

        const meta = document.createElement('div');
        meta.className = 'cas-meta';
        const date = document.createElement('span');
        date.append(makeIcon('fa-calendar-alt'), document.createTextNode(` ${formatDate(process.date)}`));
        meta.appendChild(date);
        if (process.casNumber !== null) {
            const casNumber = document.createElement('span');
            casNumber.append(makeIcon('fa-hashtag'), document.createTextNode(` ${String(process.casNumber).padStart(3, '0')}`));
            meta.appendChild(casNumber);
        }
        if (process.type) {
            const type = document.createElement('span');
            type.append(makeIcon('fa-tag'), document.createTextNode(` ${process.type}`));
            meta.appendChild(type);
        }
        body.appendChild(meta);

        const footer = document.createElement('div');
        footer.className = 'cas-card-footer';
        if (process.url) {
            const download = document.createElement('a');
            download.className = 'cas-download-btn';
            download.href = process.url;
            download.target = '_blank';
            download.rel = 'noopener noreferrer';
            download.append(makeIcon(process.format.icon), document.createTextNode(` ${process.format.label}`));

            const preview = document.createElement('button');
            preview.className = 'cas-view-btn';
            preview.type = 'button';
            preview.append(makeIcon('fa-eye'), document.createTextNode(' Vista rápida'));
            preview.setAttribute('aria-label', `Vista rápida: ${titleFor(process)}`);
            preview.addEventListener('click', () => openModal(process, preview));
            footer.append(download, preview);
        } else {
            const unavailable = document.createElement('p');
            unavailable.className = 'cas-no-results';
            unavailable.textContent = 'El documento no tiene un enlace disponible.';
            footer.appendChild(unavailable);
        }

        card.append(header, body, footer);
        return card;
    }

    function renderEmpty(message, iconClass) {
        grid.replaceChildren();
        grid.setAttribute('aria-busy', 'false');
        const state = document.createElement('div');
        state.className = 'cas-no-results';
        state.appendChild(makeIcon(iconClass));
        state.appendChild(document.createTextNode(message));
        grid.appendChild(state);
    }

    function renderResults() {
        const query = searchInput.value.trim().toLocaleLowerCase('es');
        const results = processes.filter(process => {
            const matchesYear = process.year === activeYear;
            const matchesCas = activeCas === 'all' || process.casNumber === activeCas;
            const searchable = `${titleFor(process)} ${process.processName} ${process.type} ${process.status} ${process.casNumber ?? ''} ${process.year ?? ''}`
                .toLocaleLowerCase('es');
            return matchesYear && matchesCas && searchable.includes(query);
        });

        results.sort((a, b) => sortSelect.value === 'date-asc'
            ? a.timestamp - b.timestamp
            : b.timestamp - a.timestamp);

        if (results.length === 0) {
            renderEmpty('No se encontraron documentos para esta búsqueda o filtro.', 'fa-folder-open');
            return;
        }

        grid.replaceChildren();
        grid.setAttribute('aria-busy', 'false');
        const fragment = document.createDocumentFragment();
        results.forEach(process => fragment.appendChild(renderCard(process)));
        grid.appendChild(fragment);
    }

    function openModal(process, trigger) {
        lastFocusedElement = trigger;
        modalTitle.textContent = titleFor(process);
        modalBody.replaceChildren();

        const processInfo = document.createElement('p');
        const processLabel = document.createElement('strong');
        processLabel.textContent = 'Proceso: ';
        processInfo.append(processLabel, document.createTextNode(process.processName || 'CAS'));

        const dateInfo = document.createElement('p');
        const dateLabel = document.createElement('strong');
        dateLabel.textContent = 'Fecha: ';
        dateInfo.append(dateLabel, document.createTextNode(formatDate(process.date)));
        modalBody.append(processInfo, dateInfo);

        if (process.format.preview && process.url) {
            const preview = document.createElement('div');
            preview.className = 'cas-pdf-preview';
            const frame = document.createElement('iframe');
            frame.src = process.url;
            frame.title = `Vista previa: ${titleFor(process)}`;
            frame.loading = 'lazy';
            preview.appendChild(frame);
            modalBody.appendChild(preview);
        } else {
            const notice = document.createElement('p');
            notice.className = 'cas-preview-notice';
            notice.textContent = `La vista previa no está disponible para este formato (${process.format.label.replace('Descargar ', '')}). Descarga el archivo para consultarlo.`;
            modalBody.appendChild(notice);
        }

        modalDownload.href = process.url;
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

    searchInput.addEventListener('input', renderResults);
    sortSelect.addEventListener('change', renderResults);
    modalClose.addEventListener('click', closeModal);
    modal.addEventListener('click', event => {
        if (event.target === modal) closeModal();
    });
    document.addEventListener('keydown', event => {
        if (event.key === 'Escape') closeModal();
    });

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
                        : Array.isArray(response.procesos)
                            ? response.procesos
                            : null;
            if (items === null) throw new Error('Respuesta inválida de la API');

            processes = items
                .filter(item => item && typeof item === 'object')
                .map(item => {
                    const processName = textValue(item.proceso);
                    const casInfo = parseCasInfo(processName);
                    const yearValue = Number(item.anio);
                    const year = Number.isInteger(yearValue) && yearValue > 0
                        ? yearValue
                        : casInfo.year || Number(textValue(item.fecha).slice(0, 4)) || null;
                    const url = normalizeUrl(item.pdf_url || item.url || item.pdf);
                    return {
                        title: textValue(item.titulo),
                        processName,
                        type: textValue(item.tipo),
                        status: textValue(item.status),
                        date: textValue(item.fecha),
                        timestamp: parseDate(item.fecha),
                        year,
                        casNumber: casInfo.number,
                        url,
                        format: fileFormat(url),
                        isNew: isNew(item)
                    };
                });

            const years = [...new Set(processes.map(process => process.year).filter(Boolean))]
                .sort((a, b) => b - a);
            activeYear = years[0] ?? null;

            if (processes.length === 0) {
                renderEmpty('No hay convocatorias CAS disponibles en este momento.', 'fa-folder-open');
                return;
            }

            renderFilters();
            renderResults();
        })
        .catch(error => {
            console.error('Error al cargar convocatorias CAS:', error);
            renderEmpty('No se pudo conectar con el servidor para cargar las convocatorias CAS.', 'fa-exclamation-triangle');
        });
})();

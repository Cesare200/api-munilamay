(function () {
    'use strict';

    const API_URL = '/api/resoluciones-gerencia';
    const API_BASE_URL = `${window.location.origin}/`;
    const grid = document.getElementById('resoluciones-gerencia-grid');
    const yearsContainer = document.getElementById('filtros-anios-gerencia');
    const searchInput = document.getElementById('search-resoluciones-gerencia');
    const sortSelect = document.getElementById('sort-gerencia');
    const modal = document.getElementById('resolucion-gerencia-modal');
    const modalDialog = modal?.querySelector('.gerencia-modal-content');
    const modalClose = modal?.querySelector('.gerencia-modal-close');
    const modalTitle = document.getElementById('modal-gerencia-title');
    const modalBody = modal?.querySelector('.gerencia-modal-body');
    const modalDownload = document.getElementById('modal-gerencia-download');

    if (!grid || !yearsContainer || !searchInput || !sortSelect
        || !modal || !modalDialog || !modalClose || !modalTitle || !modalBody || !modalDownload) {
        console.error('No se pudo inicializar Resoluciones de Gerencia: faltan elementos requeridos en la página.');
        return;
    }

    let resolutions = [];
    let activeYear = 'all';
    let lastFocusedElement = null;

    function textValue(value) {
        return typeof value === 'string' || typeof value === 'number' ? String(value).trim() : '';
    }

    function numberValue(value) {
        const match = textValue(value).match(/\d+/);
        return match ? Number(match[0]) : 0;
    }

    function parseYear(item, date) {
        const year = Number(item.anio);
        if (Number.isInteger(year) && year > 0) return year;
        const dateMatch = textValue(date).match(/^(\d{4})-/);
        if (dateMatch) return Number(dateMatch[1]);
        return null;
    }

    function parseDate(value) {
        const dateText = textValue(value);
        const isoMatch = dateText.match(/^(\d{4})-(\d{2})-(\d{2})/);
        if (isoMatch) {
            return new Date(Number(isoMatch[1]), Number(isoMatch[2]) - 1, Number(isoMatch[3])).getTime();
        }
        const timestamp = Date.parse(dateText);
        return Number.isNaN(timestamp) ? 0 : timestamp;
    }

    function formatDate(value) {
        const dateText = textValue(value);
        const isoMatch = dateText.match(/^(\d{4})-(\d{2})-(\d{2})/);
        if (isoMatch) return `${isoMatch[3]}/${isoMatch[2]}/${isoMatch[1]}`;
        return dateText || 'Sin fecha';
    }

    function parseUrl(value) {
        const rawUrl = textValue(value);
        if (!rawUrl || rawUrl.startsWith('//')) return null;
        try {
            const url = new URL(rawUrl, API_BASE_URL);
            return ['http:', 'https:'].includes(url.protocol) ? url.href : null;
        } catch {
            return null;
        }
    }

    function isNew(item, timestamp) {
        if (textValue(item.status).toLowerCase() === 'nuevo' || item.status === true) return true;
        if (!timestamp) return false;
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const published = new Date(timestamp);
        published.setHours(0, 0, 0, 0);
        const ageDays = (today.getTime() - published.getTime()) / 86400000;
        return ageDays >= 0 && ageDays <= 15;
    }

    function makeIcon(className) {
        const icon = document.createElement('i');
        icon.className = `fas ${className}`;
        icon.setAttribute('aria-hidden', 'true');
        return icon;
    }

    function titleFor(resolution) {
        return `RESOLUCIÓN DE GERENCIA MUNICIPAL N.° ${resolution.formattedNumber}-${resolution.year ?? 's/f'}-GM-MDL-C`;
    }

    function renderYearFilters() {
        yearsContainer.replaceChildren();
        const filters = [{ value: 'all', label: 'Todas' }];
        [...new Set(resolutions.map(resolution => resolution.year).filter(Boolean))]
            .sort((a, b) => b - a)
            .forEach(year => filters.push({ value: year, label: String(year) }));

        filters.forEach(filter => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = `filter-btn${String(filter.value) === String(activeYear) ? ' active' : ''}`;
            button.textContent = filter.label;
            button.setAttribute('aria-pressed', String(String(filter.value) === String(activeYear)));
            button.addEventListener('click', () => {
                activeYear = filter.value;
                renderYearFilters();
                applyFilters();
            });
            yearsContainer.appendChild(button);
        });
    }

    function openModal(resolution, trigger) {
        lastFocusedElement = trigger;
        const title = titleFor(resolution);
        modalTitle.textContent = title;
        modalBody.replaceChildren();

        const date = document.createElement('p');
        const dateLabel = document.createElement('strong');
        dateLabel.textContent = 'Fecha de emisión: ';
        date.append(dateLabel, document.createTextNode(formatDate(resolution.date)));

        const number = document.createElement('p');
        const numberLabel = document.createElement('strong');
        numberLabel.textContent = 'Número de resolución: ';
        number.append(numberLabel, document.createTextNode(`${resolution.formattedNumber}-${resolution.year ?? 's/f'}`));
        modalBody.append(date, number);

        const preview = document.createElement('div');
        preview.className = 'gerencia-pdf-preview';
        const frame = document.createElement('iframe');
        frame.src = resolution.pdf;
        frame.title = title;
        frame.loading = 'lazy';
        preview.appendChild(frame);
        modalBody.appendChild(preview);

        modalDownload.href = resolution.pdf;
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

    function renderCard(resolution) {
        const card = document.createElement('article');
        card.className = 'gerencia-card';

        const header = document.createElement('div');
        header.className = 'gerencia-card-header';
        if (resolution.isNew) {
            const badge = document.createElement('span');
            badge.className = 'gerencia-badge-new';
            badge.textContent = 'Nuevo';
            header.appendChild(badge);
        }
        const year = document.createElement('span');
        year.className = 'gerencia-year';
        year.textContent = resolution.year ? String(resolution.year) : '—';
        header.appendChild(year);

        const body = document.createElement('div');
        body.className = 'gerencia-card-body';
        const title = document.createElement('h2');
        title.className = 'gerencia-title';
        title.textContent = titleFor(resolution);
        body.appendChild(title);

        const meta = document.createElement('div');
        meta.className = 'gerencia-meta';
        const date = document.createElement('span');
        date.className = 'meta-date';
        date.append(makeIcon('fa-calendar-alt'), document.createTextNode(` ${formatDate(resolution.date)}`));
        meta.appendChild(date);
        const number = document.createElement('span');
        number.className = 'meta-number';
        number.append(makeIcon('fa-hashtag'), document.createTextNode(` ${resolution.formattedNumber}`));
        meta.appendChild(number);
        body.appendChild(meta);

        const footer = document.createElement('div');
        footer.className = 'gerencia-card-footer';
        if (resolution.pdf) {
            const download = document.createElement('a');
            download.className = 'download-btn';
            download.href = resolution.pdf;
            download.target = '_blank';
            download.rel = 'noopener noreferrer';
            download.setAttribute('aria-label', `Abrir PDF: ${titleFor(resolution)}`);
            download.append(makeIcon('fa-download'), document.createTextNode(' Descargar PDF'));

            const preview = document.createElement('button');
            preview.className = 'view-btn';
            preview.type = 'button';
            preview.append(makeIcon('fa-eye'), document.createTextNode(' Vista previa'));
            preview.addEventListener('click', () => openModal(resolution, preview));
            footer.append(download, preview);
        } else {
            const message = document.createElement('p');
            message.className = 'document-unavailable';
            message.textContent = 'El documento no tiene un enlace disponible.';
            footer.appendChild(message);
        }

        card.append(header, body, footer);
        return card;
    }

    function renderResults(results) {
        grid.replaceChildren();
        grid.setAttribute('aria-busy', 'false');
        if (results.length === 0) {
            const empty = document.createElement('div');
            empty.className = 'gerencia-empty';
            empty.appendChild(makeIcon('fa-folder-open'));
            const message = document.createElement('span');
            message.textContent = 'No se encontraron resoluciones de gerencia coincidentes.';
            empty.appendChild(message);
            grid.appendChild(empty);
            return;
        }

        const fragment = document.createDocumentFragment();
        results.forEach(resolution => fragment.appendChild(renderCard(resolution)));
        grid.appendChild(fragment);
    }

    function applyFilters() {
        const query = searchInput.value.trim().toLocaleLowerCase('es');
        const results = resolutions.filter(resolution => {
            const matchesYear = activeYear === 'all' || resolution.year === activeYear;
            const text = `${resolution.number} ${resolution.formattedNumber} ${resolution.year ?? ''} ${titleFor(resolution)}`.toLocaleLowerCase('es');
            return matchesYear && text.includes(query);
        });

        switch (sortSelect.value) {
            case 'date-asc':
                results.sort((a, b) => a.timestamp - b.timestamp || a.number - b.number);
                break;
            case 'number-desc':
                results.sort((a, b) => b.number - a.number || b.timestamp - a.timestamp);
                break;
            case 'number-asc':
                results.sort((a, b) => a.number - b.number || a.timestamp - b.timestamp);
                break;
            default:
                results.sort((a, b) => b.timestamp - a.timestamp || b.number - a.number);
        }

        renderResults(results);
    }

    searchInput.addEventListener('input', applyFilters);
    sortSelect.addEventListener('change', applyFilters);
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
                : response.status === 'success' && Array.isArray(response.data)
                    ? response.data
                    : response.status === 'success' && Array.isArray(response.data?.data)
                        ? response.data.data
                        : null;
            if (items === null) throw new Error('Respuesta inválida de la API');

            resolutions = items
                .filter(item => item && typeof item === 'object')
                .map(item => {
                    const date = textValue(item.fecha);
                    const year = parseYear(item, date);
                    const number = numberValue(item.numero);
                    const timestamp = parseDate(date);
                    return {
                        number,
                        formattedNumber: String(number).padStart(3, '0'),
                        year,
                        date,
                        timestamp,
                        pdf: parseUrl(item.pdf_url || item.pdf),
                        isNew: isNew(item, timestamp)
                    };
                });

            renderYearFilters();
            applyFilters();
        })
        .catch(error => {
            console.error('Error al cargar resoluciones de gerencia:', error);
            grid.replaceChildren();
            grid.setAttribute('aria-busy', 'false');
            const state = document.createElement('div');
            state.className = 'gerencia-empty';
            state.appendChild(makeIcon('fa-exclamation-triangle'));
            const message = document.createElement('span');
            message.textContent = 'No se pudo conectar con el servidor para obtener las resoluciones.';
            state.appendChild(message);
            grid.appendChild(state);
        });
})();

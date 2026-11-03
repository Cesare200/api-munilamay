(function () {
    'use strict';

    const API_URL = '/api/decretos';
    const API_BASE_URL = `${window.location.origin}/`;
    const grid = document.getElementById('decretos-grid');
    const yearsContainer = document.getElementById('year-filters');
    const searchInput = document.getElementById('search-decretos');
    const sortSelect = document.getElementById('sort-by-decretos');
    const pagination = document.getElementById('pagination-container');
    const modal = document.getElementById('decreto-modal');
    const modalDialog = modal?.querySelector('.decreto-modal-content');
    const modalClose = modal?.querySelector('.decreto-modal-close');
    const modalTitle = document.getElementById('modal-title-decreto');
    const modalBody = modal?.querySelector('.decreto-modal-body');
    const modalDownload = document.getElementById('modal-download-link');

    if (!grid || !yearsContainer || !searchInput || !sortSelect || !pagination
        || !modal || !modalDialog || !modalClose || !modalTitle || !modalBody || !modalDownload) return;

    const pageSize = 9;
    let decrees = [];
    let activeYear = 'all';
    let currentPage = 1;
    let lastFocusedElement = null;

    function textValue(value) {
        return typeof value === 'string' || typeof value === 'number' ? String(value).trim() : '';
    }

    function documentUrl(value) {
        const rawUrl = textValue(value);
        if (!rawUrl || rawUrl.startsWith('//')) return null;

        try {
            const url = new URL(rawUrl, API_BASE_URL);
            return ['http:', 'https:'].includes(url.protocol) ? url.href : null;
        } catch {
            return null;
        }
    }

    function parseDate(value) {
        const dateText = textValue(value);
        const isoMatch = dateText.match(/^(\d{4})-(\d{2})-(\d{2})/);
        if (isoMatch) {
            return new Date(Number(isoMatch[1]), Number(isoMatch[2]) - 1, Number(isoMatch[3])).getTime();
        }

        const localMatch = dateText.match(/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/);
        if (localMatch) {
            return new Date(Number(localMatch[3]), Number(localMatch[2]) - 1, Number(localMatch[1])).getTime();
        }

        const timestamp = Date.parse(dateText);
        return Number.isNaN(timestamp) ? 0 : timestamp;
    }

    function formatDate(value) {
        const dateText = textValue(value);
        const isoMatch = dateText.match(/^(\d{4})-(\d{2})-(\d{2})/);
        if (isoMatch) return `${isoMatch[3]}/${isoMatch[2]}/${isoMatch[1]}`;

        const localMatch = dateText.match(/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/);
        if (localMatch) {
            return `${localMatch[1].padStart(2, '0')}/${localMatch[2].padStart(2, '0')}/${localMatch[3]}`;
        }

        return dateText || 'Sin fecha';
    }

    function numberValue(value) {
        const digits = textValue(value).replace(/\D/g, '');
        return Number.parseInt(digits, 10) || 0;
    }

    function parseYear(item, date) {
        const year = Number(item.anio);
        if (Number.isInteger(year) && year > 0) return year;
        const dateMatch = textValue(date).match(/^(\d{4})-/);
        if (dateMatch) return Number(dateMatch[1]);
        const numberMatch = textValue(item.numero).match(/\b(20\d{2})\b/);
        return numberMatch ? Number(numberMatch[1]) : null;
    }

    function makeIcon(className) {
        const icon = document.createElement('i');
        icon.className = `fas ${className}`;
        icon.setAttribute('aria-hidden', 'true');
        return icon;
    }

    function renderYearFilters() {
        yearsContainer.replaceChildren();
        const filters = [{ value: 'all', label: 'Todos' }];
        const years = [...new Set(decrees.map(decree => decree.year).filter(Boolean))]
            .sort((a, b) => b - a);
        years.forEach(year => filters.push({ value: year, label: String(year) }));

        filters.forEach(filter => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = `filter-btn${String(filter.value) === String(activeYear) ? ' active' : ''}`;
            button.textContent = filter.label;
            button.setAttribute('aria-pressed', String(String(filter.value) === String(activeYear)));
            button.addEventListener('click', () => {
                activeYear = filter.value;
                currentPage = 1;
                renderYearFilters();
                applyFilters();
            });
            yearsContainer.appendChild(button);
        });
    }

    function renderCard(decree) {
        const card = document.createElement('article');
        card.className = 'decreto-card';

        const header = document.createElement('div');
        header.className = 'decreto-card-header';
        const year = document.createElement('span');
        year.className = 'decreto-year';
        year.textContent = decree.year ? String(decree.year) : 'S/A';
        header.appendChild(year);

        const body = document.createElement('div');
        body.className = 'decreto-card-body';
        const title = document.createElement('h2');
        title.className = 'decreto-title';
        title.textContent = decree.title;
        body.appendChild(title);

        const meta = document.createElement('div');
        meta.className = 'decreto-meta';
        const date = document.createElement('span');
        date.className = 'meta-date';
        date.append(makeIcon('fa-calendar-alt'), document.createTextNode(` ${formatDate(decree.date)}`));
        meta.appendChild(date);
        const number = document.createElement('span');
        number.className = 'meta-number';
        number.append(makeIcon('fa-hashtag'), document.createTextNode(` ${decree.number || '—'}`));
        meta.appendChild(number);
        body.appendChild(meta);

        const footer = document.createElement('div');
        footer.className = 'decreto-card-footer';
        if (decree.pdf) {
            const download = document.createElement('a');
            download.className = 'download-btn';
            download.href = decree.pdf;
            download.target = '_blank';
            download.rel = 'noopener noreferrer';
            download.setAttribute('aria-label', `Abrir PDF del decreto ${decree.number || ''}`);
            download.append(makeIcon('fa-download'), document.createTextNode(' Descargar PDF'));

            const preview = document.createElement('button');
            preview.className = 'view-btn';
            preview.type = 'button';
            preview.append(makeIcon('fa-eye'), document.createTextNode(' Vista rápida'));
            preview.addEventListener('click', () => openModal(decree, preview));
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

    function renderPagination(totalItems) {
        pagination.replaceChildren();
        const pageCount = Math.ceil(totalItems / pageSize);
        if (pageCount <= 1) return;

        const previous = document.createElement('button');
        previous.type = 'button';
        previous.disabled = currentPage === 1;
        previous.setAttribute('aria-label', 'Página anterior');
        previous.appendChild(makeIcon('fa-chevron-left'));
        previous.addEventListener('click', () => {
            currentPage -= 1;
            applyFilters();
        });

        const info = document.createElement('span');
        info.className = 'page-info';
        info.textContent = `Página ${currentPage} de ${pageCount}`;

        const next = document.createElement('button');
        next.type = 'button';
        next.disabled = currentPage >= pageCount;
        next.setAttribute('aria-label', 'Página siguiente');
        next.appendChild(makeIcon('fa-chevron-right'));
        next.addEventListener('click', () => {
            currentPage += 1;
            applyFilters();
        });

        pagination.append(previous, info, next);
    }

    function renderResults(results) {
        grid.replaceChildren();
        grid.setAttribute('aria-busy', 'false');

        if (results.length === 0) {
            const empty = document.createElement('div');
            empty.className = 'decretos-empty';
            empty.appendChild(makeIcon('fa-folder-open'));
            const message = document.createElement('span');
            message.textContent = 'No se encontraron decretos disponibles.';
            empty.appendChild(message);
            grid.appendChild(empty);
            renderPagination(0);
            return;
        }

        const pageCount = Math.ceil(results.length / pageSize);
        currentPage = Math.min(currentPage, pageCount);
        const pageResults = results.slice((currentPage - 1) * pageSize, currentPage * pageSize);
        const fragment = document.createDocumentFragment();
        pageResults.forEach(decree => fragment.appendChild(renderCard(decree)));
        grid.appendChild(fragment);
        renderPagination(results.length);
    }

    function applyFilters() {
        const query = searchInput.value.trim().toLocaleLowerCase('es');
        const results = decrees.filter(decree => {
            const matchesYear = activeYear === 'all' || decree.year === activeYear;
            const text = `${decree.number} ${decree.year ?? ''} ${decree.title}`.toLocaleLowerCase('es');
            return matchesYear && text.includes(query);
        });

        switch (sortSelect.value) {
            case 'date-asc':
                results.sort((a, b) => a.timestamp - b.timestamp || a.numberValue - b.numberValue);
                break;
            case 'number-desc':
                results.sort((a, b) => b.numberValue - a.numberValue || b.timestamp - a.timestamp);
                break;
            case 'number-asc':
                results.sort((a, b) => a.numberValue - b.numberValue || a.timestamp - b.timestamp);
                break;
            default:
                results.sort((a, b) => b.timestamp - a.timestamp || b.numberValue - a.numberValue);
        }

        renderResults(results);
    }

    function openModal(decree, trigger) {
        lastFocusedElement = trigger;
        modalTitle.textContent = decree.title;
        modalBody.replaceChildren();

        const date = document.createElement('p');
        const dateLabel = document.createElement('strong');
        dateLabel.textContent = 'Fecha de emisión: ';
        date.append(dateLabel, document.createTextNode(formatDate(decree.date)));

        const number = document.createElement('p');
        const numberLabel = document.createElement('strong');
        numberLabel.textContent = 'Número de norma: ';
        number.append(numberLabel, document.createTextNode(decree.number || 'No disponible'));
        modalBody.append(date, number);

        const preview = document.createElement('div');
        preview.className = 'pdf-preview';
        const frame = document.createElement('iframe');
        frame.src = decree.pdf;
        frame.title = `Vista previa: ${decree.title}`;
        frame.loading = 'lazy';
        preview.appendChild(frame);
        modalBody.appendChild(preview);

        modalDownload.href = decree.pdf;
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

    searchInput.addEventListener('input', () => {
        currentPage = 1;
        applyFilters();
    });
    sortSelect.addEventListener('change', () => {
        currentPage = 1;
        applyFilters();
    });
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

            decrees = items
                .filter(item => item && typeof item === 'object')
                .map(item => {
                    const rawNumber = textValue(item.numero);
                    const numberValueParsed = numberValue(rawNumber);
                    const date = textValue(item.fecha);
                    const year = Number(item.anio) || Number(date.match(/^(\d{4})/)?.[1]) || null;
                    const title = `DECRETO DE ALCALDÍA N.° ${rawNumber || String(numberValueParsed).padStart(3, '0')}`;
                    const timestamp = parseDate(date);
                    return {
                        number: rawNumber || String(numberValueParsed).padStart(3, '0'),
                        numberValue: numberValueParsed,
                        year,
                        date,
                        timestamp,
                        title,
                        pdf: documentUrl(item.pdf_url || item.pdf)
                    };
                });

            renderYearFilters();
            applyFilters();
        })
        .catch(error => {
            console.error('Error al cargar decretos municipales:', error);
            grid.replaceChildren();
            grid.setAttribute('aria-busy', 'false');
            const state = document.createElement('div');
            state.className = 'decretos-empty';
            state.appendChild(makeIcon('fa-exclamation-triangle'));
            const message = document.createElement('span');
            message.textContent = 'No se pudieron cargar los decretos desde el servidor.';
            state.appendChild(message);
            grid.appendChild(state);
        });
})();

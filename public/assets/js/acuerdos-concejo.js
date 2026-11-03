(function () {
    'use strict';

    const API_URL = '/api/acuerdos-concejo';
    const grid = document.getElementById('acuerdos-grid');
    const yearsContainer = document.getElementById('filtros-anios-acuerdos');
    const searchInput = document.getElementById('search-acuerdos');
    const sortSelect = document.getElementById('sort-acuerdos');

    if (!grid || !yearsContainer || !searchInput || !sortSelect) return;

    const newDays = 15;
    let agreements = [];
    let activeYear = null;

    function textValue(value) {
        return typeof value === 'string' || typeof value === 'number' ? String(value).trim() : '';
    }

    function parseDate(value) {
        const dateText = textValue(value);
        const match = dateText.match(/^(\d{4})-(\d{2})-(\d{2})$/);
        if (match) {
            return new Date(Number(match[1]), Number(match[2]) - 1, Number(match[3])).getTime();
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

        return dateText || '---';
    }

    function parseNumber(item) {
        const number = Number(item.numero);
        if (textValue(item.numero) !== '' && Number.isFinite(number)) return number;
        const match = textValue(item.titulo).match(/N[°ºo]?\s*(\d+)/i);
        return match ? Number(match[1]) : 0;
    }

    function parseYear(item, date) {
        const year = Number(item.anio);
        if (Number.isInteger(year) && year > 0) return year;
        const match = textValue(date).match(/^(\d{4})-/);
        if (match) return Number(match[1]);
        const titleMatch = textValue(item.titulo).match(/\b(20\d{2})\b/);
        return titleMatch ? Number(titleMatch[1]) : null;
    }

    function parseUrl(value) {
        const rawUrl = textValue(value);
        if (!rawUrl || rawUrl.startsWith('//')) return null;

        try {
            const url = new URL(rawUrl, window.location.origin);
            if (!['http:', 'https:'].includes(url.protocol)) return null;
            url.pathname = url.pathname.replace(/\/{2,}/g, '/');
            return url.href;
        } catch {
            return null;
        }
    }

    function isNew(item, timestamp) {
        if (textValue(item.status).toLowerCase() === 'nuevo') return true;
        if (!timestamp) return false;
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const published = new Date(timestamp);
        published.setHours(0, 0, 0, 0);
        const ageDays = (today.getTime() - published.getTime()) / 86400000;
        return ageDays >= 0 && ageDays <= newDays;
    }

    function makeIcon(className) {
        const icon = document.createElement('i');
        icon.className = `fas ${className}`;
        icon.setAttribute('aria-hidden', 'true');
        return icon;
    }

    function renderYearFilters() {
        yearsContainer.replaceChildren();
        const years = [...new Set(agreements.map(agreement => agreement.year).filter(Boolean))]
            .sort((a, b) => b - a);
        activeYear = years[0] ?? null;

        years.forEach(year => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = `filter-btn${year === activeYear ? ' active' : ''}`;
            button.textContent = `ACUERDOS ${year}`;
            button.setAttribute('aria-pressed', String(year === activeYear));
            button.addEventListener('click', () => {
                activeYear = year;
                yearsContainer.querySelectorAll('.filter-btn').forEach(filter => {
                    const selected = filter === button;
                    filter.classList.toggle('active', selected);
                    filter.setAttribute('aria-pressed', String(selected));
                });
                applyFilters();
            });
            yearsContainer.appendChild(button);
        });
    }

    function renderCard(agreement) {
        const card = document.createElement('article');
        card.className = 'acuerdo-card';

        const header = document.createElement('div');
        header.className = 'acuerdo-card-header';

        if (agreement.isNew) {
            const newBadge = document.createElement('span');
            newBadge.className = 'resolucion-badge new';
            newBadge.append(makeIcon('fa-bell'), document.createTextNode(' Nuevo'));
            header.appendChild(newBadge);
        }

        const badge = document.createElement('span');
        badge.className = 'acuerdo-badge';
        badge.textContent = 'ACUERDO DE CONCEJO';
        header.appendChild(badge);

        const year = document.createElement('span');
        year.className = 'acuerdo-year';
        year.textContent = agreement.year ? String(agreement.year) : '—';
        header.appendChild(year);

        const body = document.createElement('div');
        body.className = 'acuerdo-card-body';

        const title = document.createElement('h2');
        title.className = 'acuerdo-title';
        title.textContent = agreement.title;
        body.appendChild(title);

        const meta = document.createElement('div');
        meta.className = 'acuerdo-meta';

        const date = document.createElement('span');
        date.className = 'meta-date';
        date.append(makeIcon('fa-calendar-alt'), document.createTextNode(` ${formatDate(agreement.date)}`));
        meta.appendChild(date);

        const number = document.createElement('span');
        number.className = 'meta-number';
        number.append(makeIcon('fa-hashtag'), document.createTextNode(` ${String(agreement.number).padStart(3, '0')}`));
        meta.appendChild(number);
        body.appendChild(meta);

        const footer = document.createElement('div');
        footer.className = 'acuerdo-card-footer';
        if (agreement.pdf) {
            const link = document.createElement('a');
            link.className = 'download-btn';
            link.href = agreement.pdf;
            link.target = '_blank';
            link.rel = 'noopener noreferrer';
            link.append(makeIcon('fa-file-pdf'), document.createTextNode(' Descargar PDF'));
            footer.appendChild(link);
        } else {
            const message = document.createElement('p');
            message.className = 'agreement-unavailable';
            message.textContent = 'El documento no tiene un enlace disponible.';
            footer.appendChild(message);
        }

        card.append(header, body, footer);
        return card;
    }

    function renderEmptyState(message, icon) {
        grid.replaceChildren();
        grid.setAttribute('aria-busy', 'false');
        const state = document.createElement('div');
        state.className = 'no-results';
        state.appendChild(makeIcon(icon));
        const text = document.createElement('span');
        text.textContent = message;
        state.appendChild(text);
        grid.appendChild(state);
    }

    function renderResults(results) {
        if (results.length === 0) {
            renderEmptyState('No se encontraron acuerdos de concejo para este filtro.', 'fa-folder-open');
            return;
        }

        grid.replaceChildren();
        grid.setAttribute('aria-busy', 'false');
        const fragment = document.createDocumentFragment();
        results.forEach(agreement => fragment.appendChild(renderCard(agreement)));
        grid.appendChild(fragment);
    }

    function applyFilters() {
        const query = searchInput.value.trim().toLocaleLowerCase('es');
        const results = agreements.filter(agreement => {
            const matchesYear = activeYear === null || agreement.year === activeYear;
            const searchableText = `${agreement.number} ${agreement.year ?? ''} ${agreement.title}`.toLocaleLowerCase('es');
            return matchesYear && searchableText.includes(query);
        });

        switch (sortSelect.value) {
            case 'fecha_asc':
                results.sort((a, b) => a.timestamp - b.timestamp || a.number - b.number);
                break;
            case 'numero_desc':
                results.sort((a, b) => b.number - a.number || b.timestamp - a.timestamp);
                break;
            case 'numero_asc':
                results.sort((a, b) => a.number - b.number || a.timestamp - b.timestamp);
                break;
            default:
                results.sort((a, b) => b.timestamp - a.timestamp || b.number - a.number);
        }

        renderResults(results);
    }

    searchInput.addEventListener('input', applyFilters);
    sortSelect.addEventListener('change', applyFilters);

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
                    : null;
            if (items === null) throw new Error('Respuesta inválida de la API');

            agreements = items
                .filter(item => item && typeof item === 'object')
                .map(item => {
                    const date = textValue(item.fecha);
                    const year = parseYear(item, date);
                    const number = parseNumber(item);
                    const title = textValue(item.titulo)
                        || `ACUERDO DE CONCEJO N.° ${String(number).padStart(3, '0')}-${year ?? 's/f'}-MDL-C`;
                    const timestamp = parseDate(date);
                    return {
                        number,
                        year,
                        date,
                        timestamp,
                        title,
                        pdf: parseUrl(item.pdf_url || item.pdf),
                        isNew: isNew(item, timestamp)
                    };
                })
                .filter(agreement => agreement.pdf || agreement.title);

            renderYearFilters();
            applyFilters();
        })
        .catch(error => {
            console.error('Error al cargar acuerdos de concejo:', error);
            renderEmptyState('No se pudieron cargar los acuerdos de concejo desde el servidor.', 'fa-exclamation-triangle');
        });
})();

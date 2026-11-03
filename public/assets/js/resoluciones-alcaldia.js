(function () {
    'use strict';

    const API_URL = '/api/resoluciones-alcaldia';
    const grid = document.getElementById('resoluciones-grid');
    const yearsContainer = document.getElementById('filtros-anios-resoluciones');
    const searchInput = document.getElementById('search-resoluciones');
    const sortSelect = document.getElementById('sort-resoluciones');

    if (!grid || !yearsContainer || !searchInput || !sortSelect) return;

    const newDays = 15;
    let resolutions = [];
    let activeYear = null;

    function textValue(value) {
        return typeof value === 'string' || typeof value === 'number' ? String(value).trim() : '';
    }

    function parseDate(value) {
        const dateText = textValue(value);
        const isoMatch = dateText.match(/^(\d{4})-(\d{2})-(\d{2})$/);
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

        return dateText || '---';
    }

    function parseNumber(value) {
        const number = Number(value);
        if (textValue(value) !== '' && Number.isFinite(number)) return number;
        const match = textValue(value).match(/(\d+)/);
        return match ? Number(match[1]) : 0;
    }

    function parseYear(item, date) {
        const year = Number(item.anio);
        if (Number.isInteger(year) && year > 0) return year;
        const dateMatch = textValue(date).match(/^(\d{4})-/);
        if (dateMatch) return Number(dateMatch[1]);
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
        const years = [...new Set(resolutions.map(resolution => resolution.year).filter(Boolean))]
            .sort((a, b) => b - a);
        activeYear = years[0] ?? null;

        years.forEach(year => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = `filter-btn${year === activeYear ? ' active' : ''}`;
            button.textContent = `RESOLUCIONES ${year}`;
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

    function renderCard(resolution) {
        const card = document.createElement('article');
        card.className = 'resolucion-card';

        const header = document.createElement('div');
        header.className = 'resolucion-card-header';

        if (resolution.isNew) {
            const newBadge = document.createElement('span');
            newBadge.className = 'badge-nuevo';
            newBadge.append(makeIcon('fa-bell'), document.createTextNode(' Nuevo'));
            header.appendChild(newBadge);
        }

        const badge = document.createElement('span');
        badge.className = 'resolucion-badge';
        badge.textContent = 'RESOLUCIÓN DE ALCALDÍA';
        header.appendChild(badge);

        const year = document.createElement('span');
        year.className = 'resolucion-year';
        year.textContent = resolution.year ? String(resolution.year) : '—';
        header.appendChild(year);

        const body = document.createElement('div');
        body.className = 'resolucion-card-body';
        const title = document.createElement('h2');
        title.className = 'resolucion-title';
        title.textContent = resolution.title;
        body.appendChild(title);

        const meta = document.createElement('div');
        meta.className = 'resolucion-meta';
        const date = document.createElement('span');
        date.className = 'meta-date';
        date.append(makeIcon('fa-calendar-alt'), document.createTextNode(` ${formatDate(resolution.date)}`));
        meta.appendChild(date);

        const number = document.createElement('span');
        number.className = 'meta-number';
        number.append(makeIcon('fa-hashtag'), document.createTextNode(` ${String(resolution.number).padStart(3, '0')}`));
        meta.appendChild(number);
        body.appendChild(meta);

        const footer = document.createElement('div');
        footer.className = 'resolucion-card-footer';
        if (resolution.pdf) {
            const link = document.createElement('a');
            link.className = 'download-btn';
            link.href = resolution.pdf;
            link.target = '_blank';
            link.rel = 'noopener noreferrer';
            link.append(makeIcon('fa-file-pdf'), document.createTextNode(' Descargar PDF'));
            footer.appendChild(link);
        } else {
            const message = document.createElement('p');
            message.className = 'resolution-unavailable';
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
            renderEmptyState('No se encontraron resoluciones de alcaldía para este filtro.', 'fa-folder-open');
            return;
        }

        grid.replaceChildren();
        grid.setAttribute('aria-busy', 'false');
        const fragment = document.createDocumentFragment();
        results.forEach(resolution => fragment.appendChild(renderCard(resolution)));
        grid.appendChild(fragment);
    }

    function applyFilters() {
        const query = searchInput.value.trim().toLocaleLowerCase('es');
        const results = resolutions.filter(resolution => {
            const matchesYear = activeYear === null || resolution.year === activeYear;
            const searchableText = `${resolution.number} ${resolution.year ?? ''} ${resolution.title}`.toLocaleLowerCase('es');
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

            resolutions = items
                .filter(item => item && typeof item === 'object')
                .map(item => {
                    const date = textValue(item.fecha);
                    const year = parseYear(item, date);
                    const number = parseNumber(item.numero);
                    const title = textValue(item.titulo)
                        || `RESOLUCIÓN DE ALCALDÍA N.° ${String(number).padStart(3, '0')}-${year ?? 's/f'}-A-MDL/C`;
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
                .filter(resolution => resolution.pdf || resolution.title);

            renderYearFilters();
            applyFilters();
        })
        .catch(error => {
            console.error('Error al cargar resoluciones de alcaldía:', error);
            renderEmptyState('No se pudieron cargar las resoluciones de alcaldía desde el servidor.', 'fa-exclamation-triangle');
        });
})();

(function () {
    'use strict';

    const API_URL = '/api/ordenanzas';
    const grid = document.getElementById('ordenanzas-grid');
    const yearsContainer = document.getElementById('filtros-anios-ordenanzas');
    const searchInput = document.getElementById('search-ordenanzas');
    const sortSelect = document.getElementById('sort-ordenanzas');

    if (!grid || !yearsContainer || !searchInput || !sortSelect) return;

    const newDays = 15;
    let ordinances = [];
    let activeYear = null;

    function textValue(value) {
        return typeof value === 'string' || typeof value === 'number' ? String(value).trim() : '';
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

        return dateText || '---';
    }

    function parseNumber(value) {
        if (textValue(value) === '') return 0;
        const number = Number(value);
        if (Number.isFinite(number)) return number;
        const match = textValue(value).match(/\d+/);
        return match ? Number(match[0]) : 0;
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
        const years = [...new Set(ordinances.map(ordinance => ordinance.year).filter(Boolean))]
            .sort((a, b) => b - a);
        activeYear = years[0] ?? null;

        years.forEach(year => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = `filter-btn${year === activeYear ? ' active' : ''}`;
            button.textContent = `ORDENANZAS ${year}`;
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

    function renderCard(ordinance) {
        const card = document.createElement('article');
        card.className = 'ordenanza-card';

        const header = document.createElement('div');
        header.className = 'ordenanza-card-header';

        if (ordinance.isNew) {
            const badgeNew = document.createElement('span');
            badgeNew.className = 'badge-nuevo';
            badgeNew.append(makeIcon('fa-bell'), document.createTextNode(' Nuevo'));
            header.appendChild(badgeNew);
        }

        const badge = document.createElement('span');
        badge.className = 'ordenanza-badge';
        badge.textContent = ordinance.number === 0 ? 'REGLAMENTO' : 'ORDENANZA MUNICIPAL';
        header.appendChild(badge);

        const year = document.createElement('span');
        year.className = 'ordenanza-year';
        year.textContent = ordinance.year ? String(ordinance.year) : '—';
        header.appendChild(year);

        const body = document.createElement('div');
        body.className = 'ordenanza-card-body';
        const title = document.createElement('h2');
        title.className = 'ordenanza-title';
        title.textContent = ordinance.title;
        body.appendChild(title);

        const meta = document.createElement('div');
        meta.className = 'ordenanza-meta';
        const date = document.createElement('span');
        date.className = 'meta-date';
        date.append(makeIcon('fa-calendar-alt'), document.createTextNode(` ${formatDate(ordinance.date)}`));
        meta.appendChild(date);

        const number = document.createElement('span');
        number.className = 'meta-number';
        number.append(makeIcon('fa-hashtag'), document.createTextNode(` ${ordinance.number === 0 ? 'S/N' : String(ordinance.number).padStart(3, '0')}`));
        meta.appendChild(number);
        body.appendChild(meta);

        const footer = document.createElement('div');
        footer.className = 'ordenanza-card-footer';
        if (ordinance.pdf) {
            const download = document.createElement('a');
            download.className = 'download-btn';
            download.href = ordinance.pdf;
            download.target = '_blank';
            download.rel = 'noopener noreferrer';
            download.append(makeIcon('fa-file-pdf'), document.createTextNode(' Descargar PDF'));
            footer.appendChild(download);
        } else {
            const unavailable = document.createElement('p');
            unavailable.className = 'document-unavailable';
            unavailable.textContent = 'El documento no tiene un enlace disponible.';
            footer.appendChild(unavailable);
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
            renderEmptyState('No se encontraron ordenanzas municipales para este filtro.', 'fa-folder-open');
            return;
        }

        grid.replaceChildren();
        grid.setAttribute('aria-busy', 'false');
        const fragment = document.createDocumentFragment();
        results.forEach(ordinance => fragment.appendChild(renderCard(ordinance)));
        grid.appendChild(fragment);
    }

    function applyFilters() {
        const query = searchInput.value.trim().toLocaleLowerCase('es');
        const results = ordinances.filter(ordinance => {
            const matchesYear = activeYear === null || ordinance.year === activeYear;
            const searchableText = `${ordinance.number} ${ordinance.year ?? ''} ${ordinance.title}`.toLocaleLowerCase('es');
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
                    : response.status === 'success' && Array.isArray(response.data?.data)
                        ? response.data.data
                        : null;
            if (items === null) throw new Error('Respuesta inválida de la API');

            ordinances = items
                .filter(item => item && typeof item === 'object')
                .map(item => {
                    const date = textValue(item.fecha);
                    const year = parseYear(item, date);
                    const number = parseNumber(item.numero);
                    const timestamp = parseDate(date);
                    const title = textValue(item.titulo)
                        || (number === 0
                            ? `REGLAMENTO PARA LA ATENCIÓN DE DENUNCIAS AMBIENTALES - ${year ?? 's/f'}`
                            : `ORDENANZA MUNICIPAL N.° ${String(number).padStart(3, '0')}-${year ?? 's/f'}-MDL/C`);
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
                .filter(ordinance => ordinance.pdf || ordinance.title);

            renderYearFilters();
            applyFilters();
        })
        .catch(error => {
            console.error('Error al cargar ordenanzas municipales:', error);
            renderEmptyState('No se pudieron cargar las ordenanzas municipales desde el servidor.', 'fa-exclamation-triangle');
        });
})();

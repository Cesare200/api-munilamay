(function () {
    'use strict';

    const API_URL = '/api/ordenamiento-territorial';
    const API_STORAGE_URL = `${window.location.origin}/storage/`;
    const grid = document.getElementById('ordenamiento-grid');
    const categoriesContainer = document.getElementById('filtros-categorias-ordenamiento');
    const searchInput = document.getElementById('search-ordenamiento');
    const sortSelect = document.getElementById('sort-ordenamiento');

    if (!grid || !categoriesContainer || !searchInput || !sortSelect) return;

    const categoryConfig = {
        tomo_1_diagnostico: { label: 'Tomo I: Diagnóstico', icon: 'fa-book-open', badgeClass: 'badge-diagnostico' },
        tomo_2_propuestas: { label: 'Tomo II: Propuesta', icon: 'fa-lightbulb', badgeClass: 'badge-propuesta' },
        tomo_3_reglamento: { label: 'Tomo III: Reglamento', icon: 'fa-gavel', badgeClass: 'badge-reglamento' },
        mapa_diagnostico: { label: 'Mapas de Diagnóstico', icon: 'fa-map-location-dot', badgeClass: 'badge-mapa-diag' },
        mapa_propuesta: { label: 'Mapas de Propuesta', icon: 'fa-layer-group', badgeClass: 'badge-mapa-prop' }
    };

    let documents = [];
    let activeCategory = 'TODOS';

    function textValue(value) {
        return typeof value === 'string' || typeof value === 'number' ? String(value).trim() : '';
    }

    function documentUrl(value) {
        const rawUrl = textValue(value);
        if (!rawUrl || rawUrl.startsWith('//')) return null;

        try {
            const url = /^https?:\/\//i.test(rawUrl)
                ? new URL(rawUrl)
                : new URL(rawUrl.replace(/^\/+/, ''), API_STORAGE_URL);
            return ['http:', 'https:'].includes(url.protocol) ? url.href : null;
        } catch {
            return null;
        }
    }

    function categoryDetails(category) {
        const key = textValue(category) || 'general';
        return categoryConfig[key] || {
            label: key.replace(/_/g, ' ').toLocaleUpperCase('es'),
            icon: 'fa-file-pdf',
            badgeClass: 'badge-general'
        };
    }

    function parseDate(value) {
        const date = textValue(value);
        const localDate = date.match(/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/);
        if (localDate) {
            return new Date(Number(localDate[3]), Number(localDate[2]) - 1, Number(localDate[1])).getTime();
        }

        const timestamp = Date.parse(date);
        return Number.isNaN(timestamp) ? 0 : timestamp;
    }

    function createIcon(className) {
        const icon = document.createElement('i');
        icon.className = `fas ${className}`;
        icon.setAttribute('aria-hidden', 'true');
        return icon;
    }

    function createFilterButton(category, label, count, iconName) {
        const button = document.createElement('button');
        button.type = 'button';
        button.className = `filter-btn${category === activeCategory ? ' active' : ''}`;
        button.setAttribute('aria-pressed', String(category === activeCategory));
        button.append(createIcon(iconName), document.createTextNode(` ${label} (${count})`));
        button.addEventListener('click', () => {
            activeCategory = category;
            renderFilters();
            applyFilters();
        });
        return button;
    }

    function renderFilters() {
        categoriesContainer.replaceChildren();
        categoriesContainer.appendChild(createFilterButton('TODOS', 'TODOS', documents.length, 'fa-th-list'));

        const categoryKeys = [...new Set(documents.map(document => document.category))];
        categoryKeys.forEach(key => {
            const details = categoryDetails(key);
            const count = documents.filter(document => document.category === key).length;
            categoriesContainer.appendChild(createFilterButton(key, details.label, count, details.icon));
        });
    }

    function renderCard(documentData) {
        const details = categoryDetails(documentData.category);
        const card = document.createElement('article');
        card.className = 'ordenamiento-card';

        const cardHeader = document.createElement('div');
        cardHeader.className = 'ordenamiento-card-header';
        const badge = document.createElement('span');
        badge.className = `ordenamiento-badge ${details.badgeClass}`;
        badge.append(createIcon(details.icon), document.createTextNode(` ${details.label}`));
        cardHeader.appendChild(badge);

        const cardBody = document.createElement('div');
        cardBody.className = 'ordenamiento-card-body';
        const title = document.createElement('h2');
        title.className = 'ordenamiento-title';
        title.textContent = documentData.title || 'Documento sin título';
        cardBody.appendChild(title);

        if (documentData.subcategory) {
            const subcategory = document.createElement('div');
            subcategory.className = 'subcategoria-tag';
            subcategory.append(createIcon('fa-tag'), document.createTextNode(` ${documentData.subcategory}`));
            cardBody.appendChild(subcategory);
        }

        if (documentData.description) {
            const description = document.createElement('p');
            description.className = 'ordenamiento-desc';
            description.textContent = documentData.description;
            cardBody.appendChild(description);
        }

        const meta = document.createElement('div');
        meta.className = 'ordenamiento-meta';
        const date = document.createElement('span');
        date.className = 'meta-date';
        date.append(createIcon('fa-calendar-alt'), document.createTextNode(` Publicado: ${documentData.date || '---'}`));
        meta.appendChild(date);
        cardBody.appendChild(meta);

        const cardFooter = document.createElement('div');
        cardFooter.className = 'ordenamiento-card-footer';
        if (documentData.url) {
            const download = document.createElement('a');
            download.className = 'download-btn';
            download.href = documentData.url;
            download.target = '_blank';
            download.rel = 'noopener noreferrer';
            download.append(createIcon('fa-file-pdf'), document.createTextNode(' Descargar Documento'));
            cardFooter.appendChild(download);
        } else {
            const unavailable = document.createElement('span');
            unavailable.className = 'document-unavailable';
            unavailable.textContent = 'Enlace del documento no disponible.';
            cardFooter.appendChild(unavailable);
        }

        card.append(cardHeader, cardBody, cardFooter);
        return card;
    }

    function renderResults(results) {
        grid.replaceChildren();
        grid.setAttribute('aria-busy', 'false');

        if (results.length === 0) {
            const emptyState = document.createElement('div');
            emptyState.className = 'no-results';
            emptyState.append(createIcon('fa-folder-open'));
            const message = document.createElement('span');
            message.textContent = 'No se encontraron documentos o mapas para este filtro.';
            emptyState.appendChild(message);
            grid.appendChild(emptyState);
            return;
        }

        const fragment = document.createDocumentFragment();
        results.forEach(documentData => fragment.appendChild(renderCard(documentData)));
        grid.appendChild(fragment);
    }

    function applyFilters() {
        const query = searchInput.value.trim().toLocaleLowerCase('es');
        const results = documents.filter(documentData => {
            const matchesCategory = activeCategory === 'TODOS' || documentData.category === activeCategory;
            const searchableText = [
                documentData.title,
                documentData.category,
                documentData.subcategory,
                documentData.description
            ].join(' ').toLocaleLowerCase('es');
            return matchesCategory && searchableText.includes(query);
        });

        switch (sortSelect.value) {
            case 'titulo_desc':
                results.sort((a, b) => b.title.localeCompare(a.title, 'es'));
                break;
            case 'reciente':
                results.sort((a, b) => b.timestamp - a.timestamp || b.id - a.id);
                break;
            default:
                results.sort((a, b) => a.title.localeCompare(b.title, 'es'));
        }

        renderResults(results);
    }

    function showError(error) {
        console.error('Error al cargar documentos de ordenamiento territorial:', error);
        grid.replaceChildren();
        grid.setAttribute('aria-busy', 'false');
        const errorState = document.createElement('div');
        errorState.className = 'no-results';
        errorState.append(createIcon('fa-exclamation-triangle'));
        const message = document.createElement('span');
        message.textContent = 'No se pudieron cargar los documentos de ordenamiento territorial.';
        errorState.appendChild(message);
        grid.appendChild(errorState);
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

            documents = items
                .filter(item => item && typeof item === 'object')
                .map(item => {
                    const date = textValue(item.fecha || item.created_at);
                    return {
                        id: Number(item.id) || 0,
                        title: textValue(item.titulo),
                        category: textValue(item.categoria) || 'general',
                        subcategory: textValue(item.subcategoria),
                        description: textValue(item.descripcion),
                        url: documentUrl(item.pdf_url || item.archivo_pdf || item.pdf),
                        date: date || '---',
                        timestamp: parseDate(date)
                    };
                });

            renderFilters();
            applyFilters();
        })
        .catch(showError);
})();

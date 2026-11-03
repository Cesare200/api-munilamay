document.addEventListener('DOMContentLoaded', () => {
    const tabButtons = document.querySelectorAll('.codisec-tab-btn');
    const tabPanels = document.querySelectorAll('.codisec-tab-panel');

    tabButtons.forEach((button) => {
        button.addEventListener('click', () => {
            const target = button.getAttribute('data-target');

            tabButtons.forEach((btn) => {
                btn.classList.toggle('active', btn === button);
                btn.setAttribute('aria-selected', btn === button ? 'true' : 'false');
            });

            tabPanels.forEach((panel) => {
                const isActive = panel.id === target;
                panel.classList.toggle('active', isActive);
                panel.hidden = !isActive;
            });
        });
    });

    const accordionItems = document.querySelectorAll('.codisec-accordion-item');
    accordionItems.forEach((item) => {
        const trigger = item.querySelector('.codisec-accordion-header');
        const body = item.querySelector('.codisec-accordion-body');

        if (!trigger || !body) return;

        trigger.addEventListener('click', () => {
            const open = item.classList.contains('active');
            accordionItems.forEach((element) => {
                element.classList.remove('active');
                const header = element.querySelector('.codisec-accordion-header');
                const panel = element.querySelector('.codisec-accordion-body');
                if (header) header.setAttribute('aria-expanded', 'false');
                if (panel) panel.style.display = 'none';
            });

            if (!open) {
                item.classList.add('active');
                trigger.setAttribute('aria-expanded', 'true');
                body.style.display = 'block';
            }
        });
    });
});

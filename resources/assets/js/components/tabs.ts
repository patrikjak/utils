export function bindTabs(): void {
    document.querySelectorAll<HTMLElement>('.pj-tabs').forEach((tabsContainer) => {
        initTabs(tabsContainer);
    });
}

function initTabs(container: HTMLElement): void {
    const panels = container.querySelectorAll<HTMLElement>('.pj-tab-panel');

    if (panels.length === 0) {
        return;
    }

    const nav = document.createElement('nav');
    nav.className = 'pj-tabs-nav';
    nav.setAttribute('role', 'tablist');

    let hasActive = false;

    panels.forEach((panel, index) => {
        const label = panel.dataset.label ?? `Tab ${index + 1}`;
        const isActive = panel.hasAttribute('data-active');

        if (isActive) {
            hasActive = true;
        }

        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'pj-tab-btn';
        button.textContent = label;
        button.setAttribute('role', 'tab');
        button.setAttribute('aria-selected', isActive ? 'true' : 'false');

        if (isActive) {
            button.classList.add('active');
        }

        button.addEventListener('click', () => {
            activateTab(container, button, panel);
        });

        nav.appendChild(button);
    });

    if (!hasActive) {
        const firstButton = nav.querySelector<HTMLButtonElement>('.pj-tab-btn');
        const firstPanel = panels[0];

        if (firstButton && firstPanel) {
            firstButton.classList.add('active');
            firstButton.setAttribute('aria-selected', 'true');
            firstPanel.setAttribute('data-active', 'true');
        }
    }

    panels.forEach((panel) => {
        if (panel.hasAttribute('data-active')) {
            panel.classList.add('active');
        }
    });

    container.insertBefore(nav, container.firstChild);
}

function activateTab(container: HTMLElement, activeButton: HTMLElement, activePanel: HTMLElement): void {
    container.querySelectorAll<HTMLElement>('.pj-tab-btn').forEach((btn) => {
        btn.classList.remove('active');
        btn.setAttribute('aria-selected', 'false');
    });

    container.querySelectorAll<HTMLElement>('.pj-tab-panel').forEach((panel) => {
        panel.classList.remove('active');
    });

    activeButton.classList.add('active');
    activeButton.setAttribute('aria-selected', 'true');
    activePanel.classList.add('active');
}

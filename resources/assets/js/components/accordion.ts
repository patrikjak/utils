export function bindAccordions(): void {
    document.querySelectorAll<HTMLButtonElement>('.pj-accordion-header').forEach((header) => {
        header.addEventListener('click', () => {
            const accordion = header.closest<HTMLElement>('.pj-accordion');

            if (!accordion) {
                return;
            }

            const isOpen = accordion.classList.contains('open');
            const group = accordion.closest<HTMLElement>('.pj-accordion-group');

            if (group) {
                group.querySelectorAll<HTMLElement>('.pj-accordion.open').forEach((openItem) => {
                    if (openItem !== accordion) {
                        closeAccordion(openItem);
                    }
                });
            }

            if (isOpen) {
                closeAccordion(accordion);
            } else {
                openAccordion(accordion);
            }
        });
    });
}

function openAccordion(accordion: HTMLElement): void {
    accordion.classList.add('open');

    const header = accordion.querySelector<HTMLElement>('.pj-accordion-header');

    if (header) {
        header.setAttribute('aria-expanded', 'true');
    }
}

function closeAccordion(accordion: HTMLElement): void {
    accordion.classList.remove('open');

    const header = accordion.querySelector<HTMLElement>('.pj-accordion-header');

    if (header) {
        header.setAttribute('aria-expanded', 'false');
    }
}

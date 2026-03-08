export function bindComboboxes(): void {
    document.querySelectorAll<HTMLElement>('.pj-combobox').forEach((wrapper) => {
        initCombobox(wrapper);
    });
}

function initCombobox(wrapper: HTMLElement): void {
    const searchInput = wrapper.querySelector<HTMLInputElement>('.pj-combobox-search');
    const filterInput = wrapper.querySelector<HTMLInputElement>('.pj-combobox-filter');
    const hiddenInput = wrapper.querySelector<HTMLInputElement>('.pj-combobox-hidden');
    const list = wrapper.querySelector<HTMLElement>('.pj-combobox-list');
    const emptyMessage = wrapper.querySelector<HTMLElement>('.pj-combobox-empty');

    if (!searchInput || !hiddenInput || !list) {
        return;
    }

    searchInput.addEventListener('click', () => {
        toggleDropdown(wrapper, list, searchInput);
    });

    searchInput.addEventListener('keydown', (event) => {
        handleKeydown(event, wrapper, list, searchInput, hiddenInput);
    });

    filterInput?.addEventListener('input', () => {
        filterOptions(list, filterInput.value, emptyMessage);
    });

    list.querySelectorAll<HTMLElement>('.pj-combobox-option').forEach((option) => {
        option.addEventListener('click', () => {
            selectOption(option, wrapper, list, searchInput, hiddenInput);
        });

        option.addEventListener('mouseenter', () => {
            clearHighlight(list);
            option.classList.add('highlighted');
        });
    });

    document.addEventListener('click', (event) => {
        if (!wrapper.contains(event.target as Node)) {
            closeDropdown(wrapper, list, searchInput);
        }
    });
}

function toggleDropdown(wrapper: HTMLElement, list: HTMLElement, searchInput: HTMLInputElement): void {
    if (wrapper.classList.contains('open')) {
        closeDropdown(wrapper, list, searchInput);
    } else {
        openDropdown(wrapper, list, searchInput);
    }
}

function openDropdown(wrapper: HTMLElement, list: HTMLElement, searchInput: HTMLInputElement): void {
    wrapper.classList.add('open');
    list.removeAttribute('hidden');
    searchInput.setAttribute('aria-expanded', 'true');

    const filterInput = wrapper.querySelector<HTMLInputElement>('.pj-combobox-filter');
    filterInput?.focus();
}

function closeDropdown(wrapper: HTMLElement, list: HTMLElement, searchInput: HTMLInputElement): void {
    wrapper.classList.remove('open');
    list.setAttribute('hidden', '');
    searchInput.setAttribute('aria-expanded', 'false');
    clearHighlight(list);

    const filterInput = wrapper.querySelector<HTMLInputElement>('.pj-combobox-filter');

    if (filterInput) {
        filterInput.value = '';
        filterOptions(list, '', wrapper.querySelector<HTMLElement>('.pj-combobox-empty'));
    }
}

function selectOption(
    option: HTMLElement,
    wrapper: HTMLElement,
    list: HTMLElement,
    searchInput: HTMLInputElement,
    hiddenInput: HTMLInputElement,
): void {
    list.querySelectorAll<HTMLElement>('.pj-combobox-option').forEach((opt) => {
        opt.classList.remove('selected');
        opt.setAttribute('aria-selected', 'false');
    });

    option.classList.add('selected');
    option.setAttribute('aria-selected', 'true');
    hiddenInput.value = option.dataset.value ?? '';
    searchInput.value = option.textContent?.trim() ?? '';

    hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
    closeDropdown(wrapper, list, searchInput);
}

function handleKeydown(
    event: KeyboardEvent,
    wrapper: HTMLElement,
    list: HTMLElement,
    searchInput: HTMLInputElement,
    hiddenInput: HTMLInputElement,
): void {
    const isOpen = wrapper.classList.contains('open');

    if (event.key === 'Enter' || event.key === ' ') {
        event.preventDefault();

        if (!isOpen) {
            openDropdown(wrapper, list, searchInput);
            return;
        }

        const highlighted = list.querySelector<HTMLElement>('.pj-combobox-option.highlighted');

        if (highlighted) {
            selectOption(highlighted, wrapper, list, searchInput, hiddenInput);
        }
    }

    if (event.key === 'Escape') {
        closeDropdown(wrapper, list, searchInput);
    }

    if (event.key === 'ArrowDown') {
        event.preventDefault();

        if (!isOpen) {
            openDropdown(wrapper, list, searchInput);
        }

        navigateOptions(list, 1);
    }

    if (event.key === 'ArrowUp') {
        event.preventDefault();
        navigateOptions(list, -1);
    }
}

function navigateOptions(list: HTMLElement, direction: number): void {
    const options = Array.from(list.querySelectorAll<HTMLElement>('.pj-combobox-option:not([hidden])'));
    const currentIndex = options.findIndex((opt) => opt.classList.contains('highlighted'));
    const nextIndex = Math.max(0, Math.min(options.length - 1, currentIndex + direction));

    clearHighlight(list);

    if (options[nextIndex]) {
        options[nextIndex].classList.add('highlighted');
        options[nextIndex].scrollIntoView({ block: 'nearest' });
    }
}

function clearHighlight(list: HTMLElement): void {
    list.querySelectorAll<HTMLElement>('.pj-combobox-option.highlighted').forEach((opt) => {
        opt.classList.remove('highlighted');
    });
}

function filterOptions(list: HTMLElement, query: string, emptyMessage: HTMLElement | null): void {
    const options = list.querySelectorAll<HTMLElement>('.pj-combobox-option');
    const normalised = query.toLowerCase().trim();
    let visibleCount = 0;

    options.forEach((option) => {
        const text = option.textContent?.toLowerCase().trim() ?? '';
        const matches = text.includes(normalised);

        option.toggleAttribute('hidden', !matches);

        if (matches) {
            visibleCount++;
        }
    });

    if (emptyMessage) {
        emptyMessage.toggleAttribute('hidden', visibleCount > 0);
    }

    clearHighlight(list);
}

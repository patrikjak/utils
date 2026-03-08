export function bindTagsInputs(): void {
    document.querySelectorAll<HTMLElement>('.pj-tags').forEach((wrapper) => {
        initTagsInput(wrapper);
    });
}

function initTagsInput(wrapper: HTMLElement): void {
    const control = wrapper.querySelector<HTMLElement>('.pj-tags-control');
    const input = wrapper.querySelector<HTMLInputElement>('.pj-tags-input');

    if (!control || !input) {
        return;
    }

    control.addEventListener('click', () => {
        input.focus();
    });

    input.addEventListener('keydown', (event) => {
        if (event.key === 'Enter' || event.key === ',') {
            event.preventDefault();
            addTag(control, input);
            return;
        }

        if (event.key === 'Backspace' && input.value === '') {
            removeLastTag(control, input);
        }
    });

    input.addEventListener('blur', () => {
        if (input.value.trim() !== '') {
            addTag(control, input);
        }
    });

    control.querySelectorAll<HTMLElement>('.pj-tag-chip').forEach((chip) => {
        bindChipRemove(chip, control, input);
    });
}

function addTag(control: HTMLElement, input: HTMLInputElement): void {
    const value = input.value.trim().replace(/,+$/, '');

    if (value === '') {
        return;
    }

    if (tagAlreadyExists(control, value)) {
        input.value = '';
        return;
    }

    const name = input.dataset.name ?? '';
    const chip = createChip(value, name);
    bindChipRemove(chip, control, input);

    control.insertBefore(chip, input);
    input.value = '';
    input.placeholder = '';
}

function removeLastTag(control: HTMLElement, input: HTMLInputElement): void {
    const chips = control.querySelectorAll<HTMLElement>('.pj-tag-chip');
    const last = chips[chips.length - 1];

    if (last) {
        last.remove();
        updatePlaceholder(control, input);
    }
}

function bindChipRemove(chip: HTMLElement, control: HTMLElement, input: HTMLInputElement): void {
    const removeBtn = chip.querySelector<HTMLButtonElement>('.pj-tag-remove');

    removeBtn?.addEventListener('click', () => {
        chip.remove();
        updatePlaceholder(control, input);
    });
}

function createChip(value: string, name: string): HTMLElement {
    const chip = document.createElement('span');
    chip.className = 'pj-tag-chip';

    const text = document.createElement('span');
    text.className = 'pj-tag-text';
    text.textContent = value;

    const removeBtn = document.createElement('button');
    removeBtn.type = 'button';
    removeBtn.className = 'pj-tag-remove';
    removeBtn.setAttribute('aria-label', `Remove ${value}`);
    removeBtn.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
        <line x1="18" y1="6" x2="6" y2="18"></line>
        <line x1="6" y1="6" x2="18" y2="18"></line>
    </svg>`;

    const hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.name = `${name}[]`;
    hiddenInput.value = value;

    chip.appendChild(text);
    chip.appendChild(removeBtn);
    chip.appendChild(hiddenInput);

    return chip;
}

function tagAlreadyExists(control: HTMLElement, value: string): boolean {
    const existing = control.querySelectorAll<HTMLInputElement>('input[type="hidden"]');

    for (const input of existing) {
        if (input.value.toLowerCase() === value.toLowerCase()) {
            return true;
        }
    }

    return false;
}

function updatePlaceholder(control: HTMLElement, input: HTMLInputElement): void {
    const chips = control.querySelectorAll('.pj-tag-chip');
    input.placeholder = chips.length === 0 ? (input.getAttribute('data-placeholder') ?? 'Add tag...') : '';
}

export function bindRepeaters(): void {
    document.querySelectorAll<HTMLElement>('.pj-repeater').forEach((repeater) => {
        initRepeater(repeater);
    });
}

function initRepeater(repeater: HTMLElement): void {
    const min = parseInt(repeater.dataset.min ?? '1');
    const template = repeater.querySelector<HTMLTemplateElement>('.pj-repeater-template');
    const rows = repeater.querySelector<HTMLElement>('.pj-repeater-rows');
    const addBtn = repeater.querySelector<HTMLButtonElement>('.pj-repeater-add');

    if (!template || !rows || !addBtn) {
        return;
    }

    rows.querySelectorAll<HTMLElement>('.pj-repeater-row').forEach((row) => {
        bindRowRemove(row, repeater, rows, min, addBtn);
    });

    addBtn.addEventListener('click', () => {
        const max = repeater.dataset.max ? parseInt(repeater.dataset.max) : null;
        const currentCount = rows.querySelectorAll('.pj-repeater-row').length;

        if (max !== null && currentCount >= max) {
            return;
        }

        const index = getNextIndex(rows);
        const clone = template.content.cloneNode(true) as DocumentFragment;
        const rowEl = clone.querySelector<HTMLElement>('.pj-repeater-row');

        if (!rowEl) {
            return;
        }

        rowEl.dataset.index = String(index);
        rowEl.innerHTML = rowEl.innerHTML.replaceAll('{index}', String(index));

        rows.appendChild(clone);

        const addedRow = rows.querySelector<HTMLElement>(`.pj-repeater-row[data-index="${index}"]`);

        if (addedRow) {
            bindRowRemove(addedRow, repeater, rows, min, addBtn);
            addedRow.querySelector('input, textarea, select')?.focus();
        }

        updateState(repeater, rows, min, addBtn);
    });

    updateState(repeater, rows, min, addBtn);
}

function bindRowRemove(
    row: HTMLElement,
    repeater: HTMLElement,
    rows: HTMLElement,
    min: number,
    addBtn: HTMLButtonElement,
): void {
    const removeBtn = row.querySelector<HTMLButtonElement>('.pj-repeater-remove');

    removeBtn?.addEventListener('click', () => {
        const currentCount = rows.querySelectorAll('.pj-repeater-row').length;

        if (currentCount <= min) {
            return;
        }

        row.remove();
        updateState(repeater, rows, min, addBtn);
    });
}

function updateState(
    repeater: HTMLElement,
    rows: HTMLElement,
    min: number,
    addBtn: HTMLButtonElement,
): void {
    const max = repeater.dataset.max ? parseInt(repeater.dataset.max) : null;
    const currentCount = rows.querySelectorAll('.pj-repeater-row').length;

    addBtn.disabled = max !== null && currentCount >= max;

    rows.querySelectorAll<HTMLButtonElement>('.pj-repeater-remove').forEach((btn) => {
        btn.disabled = currentCount <= min;
    });
}

function getNextIndex(rows: HTMLElement): number {
    let max = -1;

    rows.querySelectorAll<HTMLElement>('.pj-repeater-row').forEach((row) => {
        const idx = parseInt(row.dataset.index ?? '-1');

        if (idx > max) {
            max = idx;
        }
    });

    return max + 1;
}

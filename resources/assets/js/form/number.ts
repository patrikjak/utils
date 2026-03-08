export function bindNumberInputs(): void {
    document.querySelectorAll<HTMLElement>('.pj-number-control').forEach((control) => {
        const input = control.querySelector<HTMLInputElement>('input[type="number"]');
        const minusBtn = control.querySelector<HTMLButtonElement>('.pj-number-minus');
        const plusBtn = control.querySelector<HTMLButtonElement>('.pj-number-plus');

        if (!input || !minusBtn || !plusBtn) {
            return;
        }

        minusBtn.addEventListener('click', () => {
            const step = parseFloat(input.step) || 1;
            const min = input.min !== '' ? parseFloat(input.min) : null;
            const current = parseFloat(input.value) || 0;
            const next = roundFloat(current - step);

            input.value = min !== null ? String(Math.max(min, next)) : String(next);
            input.dispatchEvent(new Event('change', { bubbles: true }));
            updateButtons(input, minusBtn, plusBtn);
        });

        plusBtn.addEventListener('click', () => {
            const step = parseFloat(input.step) || 1;
            const max = input.max !== '' ? parseFloat(input.max) : null;
            const current = parseFloat(input.value) || 0;
            const next = roundFloat(current + step);

            input.value = max !== null ? String(Math.min(max, next)) : String(next);
            input.dispatchEvent(new Event('change', { bubbles: true }));
            updateButtons(input, minusBtn, plusBtn);
        });

        input.addEventListener('change', () => {
            updateButtons(input, minusBtn, plusBtn);
        });

        updateButtons(input, minusBtn, plusBtn);
    });
}

function updateButtons(
    input: HTMLInputElement,
    minusBtn: HTMLButtonElement,
    plusBtn: HTMLButtonElement,
): void {
    const current = parseFloat(input.value) || 0;
    const min = input.min !== '' ? parseFloat(input.min) : null;
    const max = input.max !== '' ? parseFloat(input.max) : null;

    minusBtn.disabled = min !== null && current <= min;
    plusBtn.disabled = max !== null && current >= max;
}

function roundFloat(value: number): number {
    return Math.round(value * 1e10) / 1e10;
}

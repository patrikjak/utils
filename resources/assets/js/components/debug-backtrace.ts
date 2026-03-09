export function bindDebugBacktraces(): void {
    document.querySelectorAll<HTMLElement>('.pj-debug-backtrace').forEach((el) => {
        bindVendorToggle(el);
        bindCollapse(el);
    });
}

function bindVendorToggle(el: HTMLElement): void {
    const toggle = el.querySelector<HTMLButtonElement>('.pj-backtrace-vendor-toggle');

    if (!toggle) {
        return;
    }

    const framesContainer = el.querySelector<HTMLElement>('.pj-debug-backtrace-frames');

    if (!framesContainer) {
        return;
    }

    toggle.addEventListener('click', () => {
        const isNowHidden = framesContainer.classList.toggle('vendor-hidden');

        toggle.setAttribute('aria-pressed', isNowHidden ? 'false' : 'true');
        toggle.textContent = isNowHidden
            ? (toggle.dataset.labelShow ?? 'Show vendor')
            : (toggle.dataset.labelHide ?? 'Hide vendor');
    });
}

function bindCollapse(el: HTMLElement): void {
    const framesContainer = el.querySelector<HTMLElement>('.pj-debug-backtrace-frames[data-collapsible="true"]');

    if (!framesContainer) {
        return;
    }

    const threshold = parseInt(framesContainer.dataset.threshold ?? '5', 10);
    const frames = Array.from(framesContainer.querySelectorAll<HTMLElement>(':scope > .pj-debug-backtrace-frame'));

    if (frames.length <= threshold) {
        return;
    }

    const hiddenCount = frames.length - threshold;

    frames.slice(threshold).forEach((frame) => {
        frame.classList.add('pj-frame-collapsed');
    });

    const expandBtn = document.createElement('button');
    expandBtn.type = 'button';
    expandBtn.className = 'pj-backtrace-expand';
    expandBtn.textContent = `Show ${hiddenCount} more frame${hiddenCount !== 1 ? 's' : ''}`;

    framesContainer.appendChild(expandBtn);

    expandBtn.addEventListener('click', () => {
        framesContainer.querySelectorAll<HTMLElement>('.pj-frame-collapsed').forEach((frame) => {
            frame.classList.remove('pj-frame-collapsed');
        });

        expandBtn.remove();
    });
}

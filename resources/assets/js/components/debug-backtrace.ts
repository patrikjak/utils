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

    const collapsibleFrames = frames.slice(threshold);
    const hiddenCount = collapsibleFrames.length;
    const labelExpand = `Show ${hiddenCount} more frame${hiddenCount !== 1 ? 's' : ''}`;
    const labelCollapse = 'Collapse';

    collapsibleFrames.forEach((frame) => {
        frame.classList.add('pj-frame-collapsed');
    });

    const toggleBtn = document.createElement('button');
    toggleBtn.type = 'button';
    toggleBtn.className = 'pj-backtrace-expand';
    toggleBtn.textContent = labelExpand;

    framesContainer.appendChild(toggleBtn);

    toggleBtn.addEventListener('click', () => {
        const isCurrentlyCollapsed = collapsibleFrames[0].classList.contains('pj-frame-collapsed');

        collapsibleFrames.forEach((frame) => {
            frame.classList.toggle('pj-frame-collapsed', !isCurrentlyCollapsed);
        });

        toggleBtn.textContent = isCurrentlyCollapsed ? labelCollapse : labelExpand;
    });
}

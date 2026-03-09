import {translator} from '../translator';

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

    toggle.textContent = translator.t('debug_backtrace.hide_vendor');

    toggle.addEventListener('click', () => {
        const isNowHidden = framesContainer.classList.toggle('vendor-hidden');

        toggle.setAttribute('aria-pressed', isNowHidden ? 'false' : 'true');
        toggle.textContent = isNowHidden
            ? translator.t('debug_backtrace.show_vendor')
            : translator.t('debug_backtrace.hide_vendor');
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
    const labelExpand = translator.t('debug_backtrace.show_more', {count: hiddenCount});
    const labelCollapse = translator.t('debug_backtrace.show_less');

    collapsibleFrames.forEach((frame) => {
        frame.classList.add('pj-frame-collapsed');
    });

    const toggleBtn = document.createElement('button');
    toggleBtn.type = 'button';
    toggleBtn.className = 'pj-backtrace-expand';
    toggleBtn.innerHTML = `<span>${labelExpand}</span><svg class="pj-backtrace-expand-chevron" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"><path fill-rule="evenodd" d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/></svg>`;

    framesContainer.appendChild(toggleBtn);

    const label = toggleBtn.querySelector<HTMLSpanElement>('span')!;

    toggleBtn.addEventListener('click', () => {
        const isCurrentlyCollapsed = collapsibleFrames[0].classList.contains('pj-frame-collapsed');

        collapsibleFrames.forEach((frame) => {
            frame.classList.toggle('pj-frame-collapsed', !isCurrentlyCollapsed);
        });

        label.textContent = isCurrentlyCollapsed ? labelCollapse : labelExpand;
        toggleBtn.classList.toggle('expanded', isCurrentlyCollapsed);
    });
}

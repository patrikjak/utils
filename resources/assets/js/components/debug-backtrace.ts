import {translator} from '../translator';

export function bindDebugBacktraces(): void {
    document.querySelectorAll<HTMLElement>('.pj-debug-backtrace').forEach((el) => {
        const onVendorToggle = bindCollapse(el);
        bindVendorToggle(el, onVendorToggle);
    });
}

function bindVendorToggle(
    el: HTMLElement,
    onVendorToggle: ((isVendorHidden: boolean) => void) | null,
): void {
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

        onVendorToggle?.(isNowHidden);
    });
}

function bindCollapse(el: HTMLElement): ((isVendorHidden: boolean) => void) | null {
    const framesContainer = el.querySelector<HTMLElement>('.pj-debug-backtrace-frames[data-collapsible="true"]');

    if (!framesContainer) {
        return null;
    }

    const threshold = parseInt(framesContainer.dataset.threshold ?? '5', 10);
    const frames = Array.from(framesContainer.querySelectorAll<HTMLElement>(':scope > .pj-debug-backtrace-frame'));

    if (frames.length <= threshold) {
        return null;
    }

    const collapsibleFrames = frames.slice(threshold);

    collapsibleFrames.forEach((frame) => {
        frame.classList.add('pj-frame-collapsed');
    });

    const toggleBtn = document.createElement('button');
    toggleBtn.type = 'button';
    toggleBtn.className = 'pj-backtrace-expand';
    toggleBtn.innerHTML = `<span></span><svg class="pj-backtrace-expand-chevron" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"><path fill-rule="evenodd" d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/></svg>`;

    framesContainer.appendChild(toggleBtn);

    const label = toggleBtn.querySelector<HTMLSpanElement>('span')!;

    const nonVendorCollapsible = (): HTMLElement[] => collapsibleFrames.filter((f) => !f.classList.contains('vendor'));
    const isCollapsed = (): boolean => collapsibleFrames[0].classList.contains('pj-frame-collapsed');
    const isVendorHidden = (): boolean => framesContainer.classList.contains('vendor-hidden');

    const effectiveCollapsible = (): HTMLElement[] =>
        isVendorHidden() ? nonVendorCollapsible() : collapsibleFrames;

    const setExpandLabel = (count: number): void => {
        label.textContent = translator.t('debug_backtrace.show_more', {count});
        toggleBtn.classList.remove('expanded');
    };

    const setCollapseLabel = (): void => {
        label.textContent = translator.t('debug_backtrace.show_less');
        toggleBtn.classList.add('expanded');
    };

    // Set initial state: collapsed, all frames visible as vendor context dictates
    setExpandLabel(collapsibleFrames.length);

    toggleBtn.addEventListener('click', () => {
        const wasCollapsed = isCollapsed();

        collapsibleFrames.forEach((frame) => {
            frame.classList.toggle('pj-frame-collapsed', !wasCollapsed);
        });

        if (wasCollapsed) {
            setCollapseLabel();
        } else {
            // Collapsing — count reflects what is actually going away (visible ones)
            setExpandLabel(effectiveCollapsible().length);
        }
    });

    // Called by bindVendorToggle whenever vendor visibility changes
    const onVendorToggle = (nowHidden: boolean): void => {
        const visible = nowHidden ? nonVendorCollapsible() : collapsibleFrames;

        if (visible.length === 0) {
            toggleBtn.hidden = true;
            return;
        }

        toggleBtn.hidden = false;

        // Only update the label when collapsed; "Show less" stays correct in either vendor state
        if (isCollapsed()) {
            setExpandLabel(visible.length);
        }
    };

    return onVendorToggle;
}

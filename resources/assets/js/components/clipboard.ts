export function bindClipboard(): void {
    document.querySelectorAll<HTMLElement>('[data-clipboard-trigger]').forEach((element) => {
        if (element.dataset.pjInitialised) {
            return;
        }

        element.dataset.pjInitialised = '1';

        element.addEventListener('click', () => {
            const value = element.dataset.clipboardValue;

            if (!value) {
                return;
            }

            copyToClipboard(value, () => markCopied(element));
        });
    });
}

function copyToClipboard(value: string, onCopied: () => void): void {
    navigator.clipboard.writeText(value).then(onCopied);
}

function markCopied(element: HTMLElement): void {
    const wrapper = element.closest<HTMLElement>('.pj-clipboard') ?? element;
    wrapper.classList.add('copied');

    setTimeout(() => wrapper.classList.remove('copied'), 2000);
}

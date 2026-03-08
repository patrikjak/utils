export function bindClipboard(): void {
    document.querySelectorAll<HTMLElement>('[data-clipboard-trigger]').forEach((element) => {
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
    if (navigator.clipboard) {
        navigator.clipboard.writeText(value).then(onCopied);

        return;
    }

    const textarea = document.createElement('textarea');
    textarea.value = value;
    textarea.style.position = 'fixed';
    textarea.style.opacity = '0';
    document.body.appendChild(textarea);
    textarea.select();
    document.execCommand('copy');
    document.body.removeChild(textarea);
    onCopied();
}

function markCopied(element: HTMLElement): void {
    const wrapper = element.closest<HTMLElement>('.pj-clipboard') ?? element;
    wrapper.classList.add('copied');

    setTimeout(() => wrapper.classList.remove('copied'), 2000);
}

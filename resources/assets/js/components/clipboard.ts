export function bindClipboard(): void {
    document.querySelectorAll<HTMLButtonElement>('.pj-clipboard-btn').forEach((button) => {
        button.addEventListener('click', () => {
            const value = button.dataset.clipboardValue;

            if (!value) {
                return;
            }

            const markCopied = (): void => {
                const wrapper = button.closest<HTMLElement>('.pj-clipboard');

                if (!wrapper) {
                    return;
                }

                wrapper.classList.add('copied');

                setTimeout(() => {
                    wrapper.classList.remove('copied');
                }, 2000);
            };

            if (navigator.clipboard) {
                navigator.clipboard.writeText(value).then(markCopied);

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
            markCopied();
        });
    });
}

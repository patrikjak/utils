export function bindClipboard(): void {
    document.querySelectorAll<HTMLButtonElement>('.pj-clipboard-btn').forEach((button) => {
        button.addEventListener('click', () => {
            const value = button.dataset.clipboardValue;

            if (!value) {
                return;
            }

            navigator.clipboard.writeText(value).then(() => {
                const wrapper = button.closest<HTMLElement>('.pj-clipboard');

                if (!wrapper) {
                    return;
                }

                wrapper.classList.add('copied');

                setTimeout(() => {
                    wrapper.classList.remove('copied');
                }, 2000);
            });
        });
    });
}

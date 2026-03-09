export function bindAlerts(): void {
    document.querySelectorAll<HTMLButtonElement>('.pj-alert .pj-alert-dismiss').forEach((button) => {
        if (button.dataset.pjInitialised) {
            return;
        }

        button.dataset.pjInitialised = '1';

        button.addEventListener('click', () => {
            const alert = button.closest<HTMLElement>('.pj-alert');

            if (alert) {
                alert.remove();
            }
        });
    });
}

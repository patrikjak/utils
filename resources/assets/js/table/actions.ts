import {getCss, insertAfter} from "../helpers/general";
import {TableWrapper} from "../interfaces/table";

export function bindShowingRowActions(tableWrapper: TableWrapper): void {
    if (tableWrapper.querySelector('.table-actions')) {
        const actionButtons: NodeListOf<HTMLElement> = tableWrapper.querySelectorAll('tr td.actions');

        actionButtons.forEach((actionButton: HTMLElement): void => {
            actionButton.querySelector('.hellip').addEventListener('click', (): void => {
                showAction(actionButton, tableWrapper.querySelector('.table-actions'));
            });
        });
    }
}

export function setActionsToDefaultPosition(tableWrapper: TableWrapper): void {
    const actions: HTMLElement = tableWrapper.querySelector('.table-actions');

    if (actions !== null) {
        insertAfter(actions, tableWrapper.querySelector('.pj-table'));
    }
}

export function doAction(tableWrapper: TableWrapper, actionId: string, callback: (rowId: string) => void): void {
    const action: HTMLElement = tableWrapper.querySelector(`.action.${actionId}`);

    action.addEventListener('click', function (): void {
        const tableActions: HTMLElement = this.closest('.table-actions');
        tableActions.style.display = 'none';

        callback(this.closest('tr').id);
    });
}

function showAction(actionButton: HTMLElement, actions: HTMLElement): void {
    actions.style.display = 'inline-block';
    actions.style.position = 'absolute';

    const actionsWidth: number = parseFloat(getCss(actions, 'width'));
    const rowHeight: number = parseFloat(getCss(actionButton, 'height'));

    actions.style.top = (rowHeight / 2) + 'px';
    actions.style.left = (0 - actionsWidth) + 'px';

    insertAfter(actions, actionButton.querySelector('.hellip'));

    setTimeout((): void => {
        bindClosingActions(actions);
    }, 0);
}

function bindClosingActions(actions: HTMLElement): void {
    document.querySelector('body').addEventListener('click', function bindClosing(e): void {
        if (e.target instanceof Element) {
            const targetIsActionButton: boolean = e.target.classList.contains('hellip') ||
                e.target.closest('.hellip') !== null;

            if (!targetIsActionButton) {
                actions.style.display = 'none';
            }

            document.querySelector('body').removeEventListener('click', bindClosing);
        }
    });
}
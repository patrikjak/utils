import {getCss, insertAfter} from "../helpers/general";
import {bindPagination} from "./pagination";
import {bindPageSizeChange} from "./page-size";
import {bindChecking, checkSavedCheckboxes, handleBulkActions} from "./checkboxes";

export function bindTableFunctions(table: HTMLElement | null = null): void {
    let tables: NodeListOf<HTMLElement> = document.querySelectorAll('.pj-table-wrapper');

    if (table !== null) {
        // @ts-ignore
        tables = [table];
    }

    tables.forEach((table: HTMLElement): void => {
        bindChecking(table);
        bindShowingRowActions(table);
        bindPagination(table);
        bindPageSizeChange(table);

        handleBulkActions(table);
        checkSavedCheckboxes(table.id);
    });
}

export function bindShowingRowActions(table: HTMLElement): void {
    if (table.querySelector('.table-actions')) {
        const actionButtons: NodeListOf<HTMLElement> = table.querySelectorAll('tr td.actions');

        actionButtons.forEach((actionButton: HTMLElement): void => {
            actionButton.querySelector('.hellip').addEventListener('click', () => {
                showAction(actionButton, table.querySelector('.table-actions'));
            });
        });
    }
}

export function doAction(table: HTMLElement, actionId: string, callback: (rowId: string) => void): void {
    const action = table.querySelector(`.action.${actionId}`);

    action.addEventListener('click', function () {
        // @ts-ignore
        action.closest('.table-actions').style.display = 'none';

        callback(this.closest('tr').id);
    });
}

function showAction(actionButton: HTMLElement, actions: HTMLElement): void {
    actions.style.display = 'inline-block';
    actions.style.position = 'absolute';

    const actionsWidth = parseFloat(getCss(actions, 'width'));
    const rowHeight = parseFloat(getCss(actionButton, 'height'));

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

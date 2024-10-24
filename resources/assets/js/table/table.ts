import {getCss, insertAfter} from "../helpers/general";
import {bindPagination} from "./pagination";
import {bindPageSizeChange} from "./page-size";

export function bindTableFunctions(table: HTMLElement | null = null): void {
    let tables: NodeListOf<HTMLElement> = document.querySelectorAll('.be-table-wrapper');

    if (table !== null) {
        // @ts-ignore
        tables = [table];
    }

    tables.forEach((table: HTMLElement): void => {
        bindChecking(table);
        bindShowingRowActions(table);
        bindPagination(table);
        bindPageSizeChange(table);
    });
}

export function bindChecking(table: HTMLElement): void {
    const headCheckbox: HTMLFormElement = table.querySelector('thead .be-checkbox input');
    const dataCheckboxes: NodeListOf<HTMLFormElement> = table.querySelectorAll('tbody .be-checkbox input');

    if (headCheckbox !== null) {
        headCheckbox.closest('.be-checkbox').addEventListener('click', (): void => {
            toggleCheckAll(headCheckbox, dataCheckboxes);
        });
    }

    checkChanges(headCheckbox, dataCheckboxes);
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

export function getChecked(table: HTMLElement): Array<string> {
    const checked = [];
    const checkboxes: NodeListOf<HTMLFormElement> = table.querySelectorAll('tbody .be-checkbox input');

    checkboxes.forEach((checkbox: HTMLFormElement): void => {
        if (checkbox.checked) {
            checked.push(checkbox.id);
        }
    });

    return checked;
}

function areCheckedAll(dataCheckboxes: NodeListOf<HTMLFormElement>): boolean {
    for (const checkbox of dataCheckboxes) {
        if (checkbox.checked === false) {
            return false;
        }
    }

    return true;
}

function isAtLeastOneChecked(dataCheckboxes: NodeListOf<HTMLFormElement>): boolean {
    for (const dataCheckbox of dataCheckboxes) {
        if (dataCheckbox.checked === true) {
            return true;
        }
    }

    return false;
}

function toggleCheckAll(headCheckbox: HTMLFormElement, dataCheckboxes: NodeListOf<HTMLFormElement>): void {
    const checkedAll = areCheckedAll(dataCheckboxes);
    headCheckbox.checked = !checkedAll;

    dataCheckboxes.forEach((checkbox: HTMLFormElement): void => {
        changeCheckboxState(checkbox, !checkedAll);
    });
}

function checkChanges(headCheckbox: HTMLFormElement, dataCheckboxes: NodeListOf<HTMLFormElement>): void {
    dataCheckboxes.forEach((checkbox: HTMLFormElement): void => {
        checkbox.closest('.be-checkbox').addEventListener('click', (): void => {
            changeCheckboxState(checkbox, !checkbox.checked);
            headCheckbox.checked = areCheckedAll(dataCheckboxes);
        });
    });
}

function toggleRowActivation(checkbox: HTMLElement, shouldBeActive: boolean): void {
    const row = checkbox.closest('tr');

    shouldBeActive ? row.classList.add('active') : row.classList.remove('active');
}

function changeCheckboxState(checkbox: HTMLFormElement, active: boolean): void {
    checkbox.checked = active;
    toggleRowActivation(checkbox, active);
}
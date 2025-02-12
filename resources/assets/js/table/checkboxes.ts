import {getData} from "../helpers/general";
import {sendRequest} from "../helpers/connector";
import notify from "../utils/notification";
import {ResponseWithMessage} from "../interfaces/general";
import {translator} from "../translator";
import {TableWrapper} from "../interfaces/table";

const boundActions = new Set();

export function bindChecking(tableWrapper: TableWrapper): void {
    const headCheckbox: HTMLInputElement = tableWrapper.querySelector('thead .pj-checkbox input');
    const checkboxes: NodeListOf<HTMLInputElement> = tableWrapper.querySelectorAll('tbody .pj-checkbox input');

    if (headCheckbox === null || checkboxes.length === 0) {
        return;
    }

    headCheckbox.checked = areCheckedAllOnPage(checkboxes);

    bindCheckAll(headCheckbox);
    bindCheckboxesChanges(headCheckbox, checkboxes);
    bindActions(tableWrapper);
}

export function areCheckedAllOnPage(checkboxes: NodeListOf<HTMLInputElement>): boolean {
    for (const checkbox of checkboxes) {
        if (checkbox.checked === false) {
            return false;
        }
    }

    return true;
}

export function checkSavedCheckboxes(tableId: string): void {
    const tablesChecked: string[] = getTableCheckedRows(tableId);

    if (tablesChecked.length === 0) {
        return;
    }

    const tableWrapper: TableWrapper = document.querySelector(`.pj-table-wrapper#${tableId}`);
    const headCheckbox: HTMLInputElement = tableWrapper.querySelector('thead .pj-checkbox input');
    const checkboxes: NodeListOf<HTMLInputElement> = tableWrapper.querySelectorAll('tbody .pj-checkbox input');

    checkboxes.forEach((checkbox: HTMLInputElement): void => {
        const rowId: string = checkbox.closest('tr').id;

        if (tablesChecked.includes(rowId)) {
            checkbox.checked = true;
            toggleRowActivation(checkbox, true);
        }
    });

    if (areCheckedAllOnPage(checkboxes)) {
        headCheckbox.checked = true;
    }
}

export function handleBulkActions(tableWrapper: TableWrapper): void {
    const bulkActionsElement: HTMLElement = tableWrapper.querySelector('.bulk-actions-wrapper');

    if (bulkActionsElement === null) {
        return;
    }

    getCheckedCount(tableWrapper.id) > 0 ? bulkActionsElement.classList.remove('hidden') : bulkActionsElement.classList.add('hidden');

    updateCheckedCheckboxesBulkActionsInfo(tableWrapper);
}

function bindCheckAll(headCheckbox: HTMLInputElement): void {
    const tableWrapper: TableWrapper = headCheckbox.closest('.pj-table-wrapper');

    headCheckbox.closest('.pj-checkbox').addEventListener('click', (): void => {
        const checkboxes: NodeListOf<HTMLInputElement> = tableWrapper.querySelectorAll('tbody .pj-checkbox input');

        toggleCheckAll(headCheckbox, checkboxes);
    });
}

function bindCheckboxesChanges(headCheckbox: HTMLInputElement, checkboxes: NodeListOf<HTMLInputElement>): void {
    const tableWrapper: TableWrapper = headCheckbox.closest('.pj-table-wrapper');

    checkboxes.forEach((checkbox: HTMLInputElement): void => {
        checkbox.closest('.pj-checkbox').addEventListener('click', (): void => {
            handleCheckboxState(checkbox, !checkbox.checked);
            handleBulkActions(tableWrapper);

            headCheckbox.checked = areCheckedAllOnPage(checkboxes);
        });
    });
}

function toggleCheckAll(headCheckbox: HTMLInputElement, checkboxes: NodeListOf<HTMLInputElement>): void {
    const tableWrapper: TableWrapper = headCheckbox.closest('.pj-table-wrapper');
    const checkedAll: boolean = areCheckedAllOnPage(checkboxes);

    headCheckbox.checked = !checkedAll;

    checkboxes.forEach((checkbox: HTMLInputElement): void => {
        handleCheckboxState(checkbox, !checkedAll);
    });

    handleBulkActions(tableWrapper);
}

function toggleRowActivation(checkbox: HTMLInputElement, shouldBeActive: boolean): void {
    const row: HTMLTableRowElement = checkbox.closest('tr');

    shouldBeActive ? row.classList.add('active') : row.classList.remove('active');
}

function handleCheckboxState(checkbox: HTMLInputElement, active: boolean): void {
    changeCheckboxState(checkbox, active);
    updateMemoryAfterChange(checkbox);
}

function changeCheckboxState(checkbox: HTMLInputElement, active: boolean): void {
    if (checkbox === null) {
        return;
    }

    checkbox.checked = active;
    toggleRowActivation(checkbox, active);
}

function getCheckedCount(tableId: string): number {
    const tablesChecked: string[] = getTableCheckedRows(tableId);

    return tablesChecked.length;
}

function updateMemoryAfterChange(checkbox: HTMLInputElement): void {
    const storageKey: string = checkbox.closest('.pj-table-wrapper').id;
    const isChecked: boolean = checkbox.checked;
    const rowId: string = checkbox.closest('tr').id;

    isChecked ? saveCheckedCheckbox(storageKey, rowId) : removeCheckedCheckbox(storageKey, rowId);
}

function saveCheckedCheckbox(tableId: string, rowId: string): void {
    const tablesChecked: string[] = getTableCheckedRows(tableId);

    if (tablesChecked.includes(rowId)) {
        return;
    }

    tablesChecked.push(rowId);
    sessionStorage.setItem(tableId, JSON.stringify(tablesChecked));
}

function removeCheckedCheckbox(tableId: string, rowId: string): void {
    const tablesChecked: string[] = getTableCheckedRows(tableId);
    const index: number = tablesChecked.indexOf(rowId);

    if (index !== -1) {
        tablesChecked.splice(index, 1);
    }

    sessionStorage.setItem(tableId, JSON.stringify(tablesChecked));
}

function updateCheckedCheckboxesBulkActionsInfo(tableWrapper: TableWrapper): void {
    const countInfo = tableWrapper.querySelector('.bulk-actions-wrapper .selected .count');

    if (countInfo === null) {
        return;
    }

    countInfo.textContent = getCheckedCount(tableWrapper.id).toString();
}

function getTableCheckedRows(tableId: string): string[] {
    return JSON.parse(sessionStorage.getItem(tableId) || '[]');
}

function removeTableCheckedRows(tableId: string): void {
    sessionStorage.removeItem(tableId);
}

function bindActions(tableWrapper: TableWrapper): void {
    const bulkActionsElement: HTMLElement = tableWrapper.querySelector('.bulk-actions-wrapper');

    if (bulkActionsElement === null) {
        return;
    }

    const actions: NodeListOf<HTMLButtonElement> = bulkActionsElement.querySelectorAll('.bulk-action button');

    actions.forEach((action: HTMLButtonElement): void => {
        if (boundActions.has(action)) {
            return;
        }

        boundActions.add(action);

        action.addEventListener('click', function handleActionButton(): void {
            handleBulkAction(action, tableWrapper);
        });
    });
}

function handleBulkAction(action: HTMLButtonElement, tableWrapper: TableWrapper): void {
    const checkedRows: string[] = getTableCheckedRows(tableWrapper.id);

    if (checkedRows.length === 0) {
        return;
    }

    const url: string = getData(action, 'action');
    const method: string = getData(action, 'method').toLowerCase();

    sendRequest(url, method, prepareBulkActionData(checkedRows)).then((response: ResponseWithMessage): void => {
        handleBulkActionSuccess(response, tableWrapper, checkedRows);
    }).catch((error: any): void => {
        console.log(error);
        notify(error?.data?.message ?? translator.t('errors.default.message'), 'Ooops!', 'error');
    });
}

function prepareBulkActionData(checkedRows: string[]): FormData {
    const data: FormData = new FormData();

    checkedRows.forEach((rowId: string): void => {
        data.append('bulkActionsIds[]', rowId);
    });

    return data;
}

function handleBulkActionSuccess(response: ResponseWithMessage, tableWrapper: TableWrapper, checkedRows: string[]): void {
    const headCheckbox: HTMLInputElement = tableWrapper.querySelector('thead .pj-checkbox input');
    headCheckbox.checked = false;

    checkedRows.forEach((rowId: string): void => {
        const checkbox: HTMLInputElement = tableWrapper.querySelector(`tr[id='${rowId}'] .pj-checkbox input`);

        if (checkbox === null) {
            return;
        }

        handleCheckboxState(tableWrapper.querySelector(`tr[id='${rowId}'] .pj-checkbox input`), false);
    });

    removeTableCheckedRows(tableWrapper.id);
    handleBulkActions(tableWrapper);
    notify(response.message, 'Super', 'success');
}
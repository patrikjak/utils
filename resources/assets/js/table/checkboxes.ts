import {getData} from "../helpers/general";
import {sendRequest} from "../helpers/connector";
import notify from "../utils/notification";
import {ResponseWithMessage} from "../interfaces/general";
import {translator} from "../translator";

const boundActions = new Set();

export function bindChecking(table: HTMLElement): void
{
    const headCheckbox: HTMLInputElement = table.querySelector('thead .pj-checkbox input');
    const checkboxes: NodeListOf<HTMLInputElement> = table.querySelectorAll('tbody .pj-checkbox input');

    if (headCheckbox === null || checkboxes.length === 0) {
        return;
    }

    headCheckbox.checked = areCheckedAllOnPage(checkboxes);

    bindCheckAll(headCheckbox);
    bindCheckboxesChanges(headCheckbox, checkboxes);
    bindActions(table);
}

function bindCheckAll(headCheckbox: HTMLInputElement): void
{
    const table: HTMLElement = headCheckbox.closest('.pj-table-wrapper');

    headCheckbox.closest('.pj-checkbox').addEventListener('click', (): void => {
        const checkboxes: NodeListOf<HTMLInputElement> = table.querySelectorAll('tbody .pj-checkbox input');

        toggleCheckAll(headCheckbox, checkboxes);
    });
}

function bindCheckboxesChanges(headCheckbox: HTMLInputElement, checkboxes: NodeListOf<HTMLInputElement>): void
{
    const table: HTMLElement = headCheckbox.closest('.pj-table-wrapper');

    checkboxes.forEach((checkbox: HTMLInputElement): void => {
        checkbox.closest('.pj-checkbox').addEventListener('click', (): void => {
            handleCheckboxState(checkbox, !checkbox.checked);
            handleBulkActions(table);

            headCheckbox.checked = areCheckedAllOnPage(checkboxes);
        });
    });
}

function toggleCheckAll(headCheckbox: HTMLInputElement, checkboxes: NodeListOf<HTMLInputElement>): void
{
    const table: HTMLElement = headCheckbox.closest('.pj-table-wrapper');
    const checkedAll: boolean = areCheckedAllOnPage(checkboxes);

    headCheckbox.checked = !checkedAll;

    checkboxes.forEach((checkbox: HTMLInputElement): void => {
        handleCheckboxState(checkbox, !checkedAll);
    });

    handleBulkActions(table);
}

export function checkSavedCheckboxes(tableId: string): void
{
    const tablesChecked: string[] = getTableCheckedRows(tableId);

    if (tablesChecked.length === 0) {
        return;
    }

    const table: HTMLElement = document.querySelector(`.pj-table-wrapper#${tableId}`);
    const headCheckbox: HTMLInputElement = table.querySelector('thead .pj-checkbox input');
    const checkboxes: NodeListOf<HTMLInputElement> = table.querySelectorAll('tbody .pj-checkbox input');

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

function toggleRowActivation(checkbox: HTMLInputElement, shouldBeActive: boolean): void
{
    const row = checkbox.closest('tr');

    shouldBeActive ? row.classList.add('active') : row.classList.remove('active');
}

function handleCheckboxState(checkbox: HTMLInputElement, active: boolean): void
{
    changeCheckboxState(checkbox, active);
    updateMemoryAfterChange(checkbox);
}

function changeCheckboxState(checkbox: HTMLInputElement, active: boolean): void
{
    if (checkbox === null) {
        return;
    }

    checkbox.checked = active;
    toggleRowActivation(checkbox, active);
}

export function areCheckedAllOnPage(checkboxes: NodeListOf<HTMLInputElement>): boolean
{
    for (const checkbox of checkboxes) {
        if (checkbox.checked === false) {
            return false;
        }
    }

    return true;
}

function getCheckedCount(tableId: string): number
{
    const tablesChecked: string[] = getTableCheckedRows(tableId);

    return tablesChecked.length;
}

function updateMemoryAfterChange(checkbox: HTMLInputElement): void
{
    const storageKey: string = checkbox.closest('.pj-table-wrapper').id;
    const isChecked: boolean = checkbox.checked;
    const rowId: string = checkbox.closest('tr').id;

    isChecked ? saveCheckedCheckbox(storageKey, rowId) : removeCheckedCheckbox(storageKey, rowId);
}

function saveCheckedCheckbox(tableId: string, rowId: string): void
{
    const tablesChecked: string[] = getTableCheckedRows(tableId);

    if (tablesChecked.includes(rowId)) {
        return;
    }

    tablesChecked.push(rowId);
    sessionStorage.setItem(tableId, JSON.stringify(tablesChecked));
}

function removeCheckedCheckbox(tableId: string, rowId: string): void
{
    const tablesChecked: string[] = getTableCheckedRows(tableId);
    const index: number = tablesChecked.indexOf(rowId);

    if (index !== -1) {
        tablesChecked.splice(index, 1);
    }

    sessionStorage.setItem(tableId, JSON.stringify(tablesChecked));
}

export function handleBulkActions(table: HTMLElement): void
{
    const bulkActionsElement: HTMLElement = table.querySelector('.bulk-actions-wrapper');

    if (bulkActionsElement === null) {
        return;
    }

    getCheckedCount(table.id) > 0 ? bulkActionsElement.classList.remove('hidden') : bulkActionsElement.classList.add('hidden');

    updateCheckedCheckboxesBulkActionsInfo(table);
}

function updateCheckedCheckboxesBulkActionsInfo(table: HTMLElement): void
{
    const countInfo = table.querySelector('.bulk-actions-wrapper .selected .count');

    if (countInfo === null) {
        return;
    }

    countInfo.textContent = getCheckedCount(table.id).toString();
}

function getTableCheckedRows(tableId: string): string[]
{
    return JSON.parse(sessionStorage.getItem(tableId) || '[]');
}

function removeTableCheckedRows(tableId: string): void
{
    sessionStorage.removeItem(tableId);
}

function bindActions(table: HTMLElement): void
{
    const bulkActionsElement: HTMLElement = table.querySelector('.bulk-actions-wrapper');

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
            handleBulkAction(action, table);
        });
    });
}

function handleBulkAction(action: HTMLButtonElement, table: HTMLElement): void
{
    const checkedRows: string[] = getTableCheckedRows(table.id);

    if (checkedRows.length === 0) {
        return;
    }

    const url: string = getData(action, 'action');
    const method: string = getData(action, 'method').toLowerCase();

    sendRequest(url, method, prepareBulkActionData(checkedRows)).then((response: ResponseWithMessage): void => {
        handleBulkActionSuccess(response, table, checkedRows);
    }).catch((error: any): void => {
        console.log(error);
        notify(error?.data?.message ?? translator.t('errors.default.message'), 'Ooops!', 'error');
    });
}

function prepareBulkActionData(checkedRows: string[]): FormData
{
    const data: FormData = new FormData();

    checkedRows.forEach((rowId: string): void => {
        data.append('bulkActionsIds[]', rowId);
    });

    return data;
}

function handleBulkActionSuccess(response: ResponseWithMessage, table: HTMLElement, checkedRows: string[]): void
{
    const headCheckbox: HTMLInputElement = table.querySelector('thead .pj-checkbox input');
    headCheckbox.checked = false;

    checkedRows.forEach((rowId: string): void => {
        const checkbox: HTMLInputElement = table.querySelector(`tr[id='${rowId}'] .pj-checkbox input`);

        if (checkbox === null) {
            return;
        }

        handleCheckboxState(table.querySelector(`tr[id='${rowId}'] .pj-checkbox input`), false);
    });

    removeTableCheckedRows(table.id);
    handleBulkActions(table);
    notify(response.message, 'Success', 'success');
}
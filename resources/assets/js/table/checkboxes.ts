export function bindChecking(table: HTMLElement): void
{
    const headCheckbox: HTMLInputElement = table.querySelector('thead .pj-checkbox input');
    const checkboxes: NodeListOf<HTMLInputElement> = table.querySelectorAll('tbody .pj-checkbox input');

    if (headCheckbox === null || checkboxes.length === 0) {
        return;
    }

    bindCheckAll(headCheckbox, checkboxes);
    bindCheckboxesChanges(headCheckbox, checkboxes);
}

function bindCheckAll(headCheckbox: HTMLInputElement, checkboxes: NodeListOf<HTMLInputElement>): void
{
    headCheckbox.closest('.pj-checkbox').addEventListener('click', (): void => {
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
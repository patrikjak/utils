import {FilterCriteria, PageCriteria, SearchCriteria, SortCriteria} from "../interfaces/table";

const storageKeyPrefix = 'pjutils_table_';

export type TableState = {
    page: number;
    pageSize: number;
    sort: string | null;
    order: string;
    filters: FilterCriteria | null;
    search: string | null;
    visibleColumns: string[] | null;
};

function getStorageKey(tableId: string): string {
    return storageKeyPrefix + tableId;
}

export function saveTableState(
    tableId: string,
    pageCriteria: PageCriteria,
    sortCriteria: SortCriteria,
    filterCriteria: FilterCriteria,
    searchCriteria: SearchCriteria,
    visibleColumns: string[] | null,
): void {
    const state: TableState = {
        page: pageCriteria.page,
        pageSize: pageCriteria.pageSize,
        sort: sortCriteria.deleteSort ? null : (sortCriteria.column ?? null),
        order: sortCriteria.order,
        filters: filterCriteria.deleteFilters ? null : filterCriteria,
        search: searchCriteria.deleteSearch ? null : searchCriteria.query,
        visibleColumns,
    };

    localStorage.setItem(getStorageKey(tableId), JSON.stringify(state));
}

export function loadTableState(tableId: string): TableState | null {
    const raw: string | null = localStorage.getItem(getStorageKey(tableId));

    if (raw === null) {
        return null;
    }

    try {
        return JSON.parse(raw) as TableState;
    } catch {
        return null;
    }
}

export function clearTableState(tableId: string): void {
    localStorage.removeItem(getStorageKey(tableId));
}

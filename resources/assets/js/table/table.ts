import {getData, urlIncludesGetParameters} from "../helpers/general";
import {bindPagination, getCurrentPage} from "./pagination";
import {bindPageSizeChange, getCurrentPageSize} from "./page-size";
import {bindChecking, checkSavedCheckboxes, handleBulkActions} from "./checkboxes";
import {bindOptions} from "./options";
import {PageCriteria, SortCriteria, TableParts, TableWrapper} from "../interfaces/table";
import {pageKey, pageSizeKey} from "./constants";
import {bindShowingRowActions, setActionsToDefaultPosition} from "./actions";
import axios from "axios";
import {bindDropdowns} from "../utils/dropdown";

export function bindTableFunctions(tableWrapper: TableWrapper | null = null): void {
    let tables: TableWrapper[] = Array.from(document.querySelectorAll('.pj-table-wrapper'));

    if (tableWrapper !== null) {
        tables = [tableWrapper];
    }

    tables.forEach((table: TableWrapper): void => {
        bindUpdate(table);
        bindFunctions(table);
    });
}

export function dispatchUpdateEvent(tableWrapper: TableWrapper, detail: object = {}): void {
    tableWrapper.dispatchEvent(new CustomEvent('update', {
        detail: detail,
    }));
}

function bindUpdate(tableWrapper: TableWrapper): void {
    tableWrapper.addEventListener('update', function (event: CustomEvent): void {
        const pageCriteria: PageCriteria = getPageCriteria(tableWrapper, event);

        setActionsToDefaultPosition(tableWrapper);

        reloadTable(tableWrapper, pageCriteria).then((): void => {
            bindFunctions(tableWrapper);
            bindDropdowns(tableWrapper);
        });
    });
}

function bindFunctions(tableWrapper: TableWrapper): void {
    bindChecking(tableWrapper);
    bindShowingRowActions(tableWrapper);
    bindPagination(tableWrapper);
    bindPageSizeChange(tableWrapper);
    bindOptions(tableWrapper);

    handleBulkActions(tableWrapper);
    checkSavedCheckboxes(tableWrapper.id);
}

function getPageCriteria(tableWrapper: TableWrapper, event: CustomEvent): PageCriteria {
    let page: number | undefined = event.detail.page;
    let pageSize: number | undefined = event.detail.pageSize;

    if (page === undefined) {
        page = getCurrentPage(tableWrapper);
    }

    if (pageSize === undefined) {
        pageSize = getCurrentPageSize(tableWrapper);
    }

    return {page, pageSize};
}

async function reloadTable(
    tableWrapper: TableWrapper,
    pageCriteria: PageCriteria | null = null,
    sortCriteria: SortCriteria | null = null,
): Promise<void> {
    const url: string = getTableUrl(tableWrapper, pageCriteria, sortCriteria);
    const tableParts: TableParts = await getTableParts(url);

    reloadTableHead(tableWrapper, tableParts.head);
    reloadTableBody(tableWrapper, tableParts.body);
    reloadTablePagination(tableWrapper, tableParts.pagination);
}

function getTableUrl(
    tableWrapper: TableWrapper,
    pageCriteria: PageCriteria|null = null,
    sortCriteria: SortCriteria|null = null
): string {
    let url: string = getData(tableWrapper, 'html-parts-url');

    if (pageCriteria !== null) {
        url = addPageCriteriaToUrl(url, pageCriteria);
    }

    if (sortCriteria !== null) {
        url = addSortCriteriaToUrl(url, sortCriteria);
    }

    return url;
}

function getTableParts(url: string): Promise<TableParts> {
    return new Promise((resolve: (tableParts: TableParts) => void): void => {
        axios.get(url).then(response => {
            resolve(response.data);
        }).catch((): void => console.log('Error while loading table parts'));
    });
}

function reloadTableHead(tableWrapper: TableWrapper, head: string): void {
    const tableHead: HTMLTableSectionElement = tableWrapper.querySelector('thead');
    tableHead.innerHTML = head;
}

function reloadTableBody(tableWrapper: TableWrapper, body: string): void {
    const tableBody: HTMLTableSectionElement = tableWrapper.querySelector('tbody');
    tableBody.innerHTML = body;
}

function reloadTablePagination(tableWrapper: TableWrapper, pagination: string): void {
    const tablePagination: HTMLElement = tableWrapper.closest('.pj-table-wrapper').querySelector('.pagination');
    tablePagination.outerHTML = pagination;
}

function addPageCriteriaToUrl(url: string, pageCriteria: PageCriteria): string {
    urlIncludesGetParameters(url) ? url += '&' : url += '?';

    return url + `${pageKey}=${pageCriteria.page}&${pageSizeKey}=${pageCriteria.pageSize}`;
}

function addSortCriteriaToUrl(url: string, sortCriteria: SortCriteria): string {
    urlIncludesGetParameters(url) ? url += '&' : url += '?';

    return url + `sort=${sortCriteria.column}&direction=${sortCriteria.direction}`;
}


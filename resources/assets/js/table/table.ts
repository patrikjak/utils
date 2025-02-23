import {getData, urlIncludesGetParameters} from "../helpers/general";
import {bindPagination, getCurrentPage} from "./pagination";
import {bindPageSizeChange, getCurrentPageSize} from "./page-size";
import {bindChecking, checkSavedCheckboxes, handleBulkActions} from "./checkboxes";
import {bindOptions} from "./options";
import {
    DateFilter,
    Filter,
    FilterCriteria,
    NumberFilter,
    PageCriteria, SelectFilter,
    SortCriteria,
    TableParts,
    TableWrapper,
    TextFilter
} from "../interfaces/table";
import {deleteFilterKey, deleteSortKey, filterKey, orderKey, pageKey, pageSizeKey, sortKey} from "./constants";
import {bindShowingRowActions, setActionsToDefaultPosition} from "./actions";
import axios from "axios";
import {bindDropdowns} from "../utils/dropdown";
import {bindSorting, getCurrentOrder, getCurrentSort} from "./sort";
import {bindFilter, getCurrentFilterCriteria} from "./filter";

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
        const sortCriteria: SortCriteria = getSortCriteria(tableWrapper, event);
        const filterCriteria: FilterCriteria = getFilterCriteria(tableWrapper, event);

        setActionsToDefaultPosition(tableWrapper);

        reloadTable(tableWrapper, pageCriteria, sortCriteria, filterCriteria).then((): void => {
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
    bindSorting(tableWrapper);
    bindFilter(tableWrapper);

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

function getSortCriteria(tableWrapper: TableWrapper, event: CustomEvent): SortCriteria {
    let column: string | undefined = event.detail.column;
    let order: string | undefined = event.detail.order;
    const deleteSort: boolean = event.detail.deleteSort ?? false;

    if (column === undefined) {
        column = getCurrentSort(tableWrapper);
    }

    if (order === undefined) {
        order = getCurrentOrder(tableWrapper) ?? 'asc';
    }

    return {column, order, deleteSort};
}

function getFilterCriteria(tableWrapper: TableWrapper, event: CustomEvent): FilterCriteria {
    let filterCriteria: FilterCriteria | undefined = event.detail.filterCriteria;

    if (filterCriteria === undefined) {
        filterCriteria = getCurrentFilterCriteria(tableWrapper);
    }

    return filterCriteria;
}

async function reloadTable(
    tableWrapper: TableWrapper,
    pageCriteria: PageCriteria | null = null,
    sortCriteria: SortCriteria | null = null,
    filterCriteria: FilterCriteria | null = null,
): Promise<void> {
    const url: string = getTableUrl(tableWrapper, pageCriteria, sortCriteria, filterCriteria);
    const tableParts: TableParts = await getTableParts(url);

    reloadTableHead(tableWrapper, tableParts.head);
    reloadTableBody(tableWrapper, tableParts.body);

    if (tableParts.pagination) {
        reloadTablePagination(tableWrapper, tableParts.pagination);
    }

    if (tableParts.options) {
        reloadOptions(tableWrapper, tableParts.options);
    }
}

function getTableUrl(
    tableWrapper: TableWrapper,
    pageCriteria: PageCriteria | null = null,
    sortCriteria: SortCriteria | null = null,
    filterCriteria: FilterCriteria | null = null,
): string {
    let url: string = getData(tableWrapper, 'html-parts-url');

    if (pageCriteria !== null) {
        url = addPageCriteriaToUrl(url, pageCriteria);
    }

    if (sortCriteria !== null) {
        url = addSortCriteriaToUrl(url, sortCriteria);
    }

    if (filterCriteria !== null) {
        url = addFilterCriteriaToUrl(url, filterCriteria);
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

function reloadOptions(tableWrapper: TableWrapper, options: string): void {
    const tableOptions: HTMLElement = tableWrapper.querySelector('.table-options');
    tableOptions.outerHTML = options;
}

function addPageCriteriaToUrl(url: string, pageCriteria: PageCriteria): string {
    urlIncludesGetParameters(url) ? url += '&' : url += '?';

    return url + `${pageKey}=${pageCriteria.page}&${pageSizeKey}=${pageCriteria.pageSize}`;
}

function addSortCriteriaToUrl(url: string, sortCriteria: SortCriteria): string {
    if (sortCriteria.column === null) {
        return url;
    }

    urlIncludesGetParameters(url) ? url += '&' : url += '?';

    return url + `${sortKey}=${sortCriteria.column}&${orderKey}=${sortCriteria.order}&${deleteSortKey}=${sortCriteria.deleteSort}`;
}

function addFilterCriteriaToUrl(url: string, filterCriteria: FilterCriteria): string {
    if (filterCriteria?.deleteFilters) {
        urlIncludesGetParameters(url) ? url += '&' : url += '?';
        url =  url + `${deleteFilterKey}=${filterCriteria.deleteFilters}`;
    }

    if (filterCriteria === null || filterCriteria.filters.length === 0) {
        return url;
    }

    urlIncludesGetParameters(url) ? url += '&' : url += '?';

    let filterQueries: string[] = [];
    let i: number = 0;

    for (const filter of filterCriteria.filters) {
        filterQueries.push(getFilterQuery(filter, i));
        i++;
    }

    return url + filterQueries.join('&') + `&${deleteFilterKey}=${filterCriteria.deleteFilters}`;
}

function getFilterQuery(filter: Filter, index: number): string {
    switch (filter.type) {
        case 'text':
            return getTextFilterQuery(<TextFilter> filter, index);
        case 'number':
        case 'date':
            return getRangeFilterQuery(<NumberFilter | DateFilter> filter, index);
        case 'select':
            return getSelectFilterQuery(<SelectFilter> filter, index);
    }
}

function getTextFilterQuery(filter: TextFilter, index: number): string {
    return `${filterKey}[${filter.column}][${index}][value]=${filter.value}
        &${filterKey}[${filter.column}][${index}][operator]=${filter.filterType}
        &${filterKey}[${filter.column}][${index}][type]=${filter.type}`;
}

function getRangeFilterQuery(filter: NumberFilter | DateFilter, index: number): string {
    if (filter.from === null && filter.to === null) {
        return '';
    }

    const filterTypeQuery: string = `&${filterKey}[${filter.column}][${index}][type]=${filter.type}`;

    if (filter.from === null) {
        return `${filterKey}[${filter.column}][${index}][to]=${filter.to}${filterTypeQuery}`;
    }

    if (filter.to === null) {
        return `${filterKey}[${filter.column}][${index}][from]=${filter.from}${filterTypeQuery}`;
    }

    return `${filterKey}[${filter.column}][${index}][from]=${filter.from}
        &${filterKey}[${filter.column}][${index}][to]=${filter.to}${filterTypeQuery}`;
}

function getSelectFilterQuery(filter: SelectFilter, index: number): string {
    return `${filterKey}[${filter.column}][${index}][value]=${filter.value}
        &${filterKey}[${filter.column}][${index}][type]=${filter.type}`;
}
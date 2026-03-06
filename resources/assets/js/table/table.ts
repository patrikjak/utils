import {getData, urlIncludesGetParameters} from "../helpers/general";
import {bindPagination, getCurrentPage} from "./pagination";
import {bindPageSizeChange, getCurrentPageSize} from "./page-size";
import {bindChecking, checkSavedCheckboxes, handleBulkActions} from "./checkboxes";
import {bindOptions} from "./options";
import {
    DateFilter,
    Filter,
    FilterCriteria,
    JsonFilter,
    NumberFilter,
    PageCriteria,
    SelectFilter,
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
import {loadTableState, saveTableState, TableStorageData} from "./storage";

export function bindTableFunctions(tableWrapper: TableWrapper | null = null): void {
    let tables: TableWrapper[] = Array.from(document.querySelectorAll('.pj-table-wrapper'));

    if (tableWrapper !== null) {
        tables = [tableWrapper];
    }

    tables.forEach((table: TableWrapper): void => {
        bindUpdate(table);
        bindFunctions(table);
        restoreTableState(table);
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
    bindTooltips(tableWrapper);

    handleBulkActions(tableWrapper);
    checkSavedCheckboxes(tableWrapper.id);
}

let activeTooltip: HTMLElement | null = null;
let hideTooltipTimeout: ReturnType<typeof setTimeout> | null = null;

function bindTooltips(tableWrapper: TableWrapper): void {
    const elements: NodeListOf<HTMLElement> = tableWrapper.querySelectorAll('.truncated[data-tooltip]');

    elements.forEach((el: HTMLElement): void => {
        el.addEventListener('mouseenter', showTooltip);
        el.addEventListener('mouseleave', scheduleHideTooltip);
    });
}

function showTooltip(event: MouseEvent): void {
    cancelHideTooltip();

    const target: HTMLElement = event.currentTarget as HTMLElement;
    const text: string | null = target.getAttribute('data-tooltip');

    if (text === null) {
        return;
    }

    if (activeTooltip !== null) {
        activeTooltip.remove();
    }

    const tooltip: HTMLDivElement = document.createElement('div');
    tooltip.className = 'pj-table-tooltip';
    tooltip.textContent = text;
    document.body.appendChild(tooltip);

    const targetRect: DOMRect = target.getBoundingClientRect();
    const tooltipRect: DOMRect = tooltip.getBoundingClientRect();

    const top: number = targetRect.top - tooltipRect.height - 6;
    const centeredLeft: number = targetRect.left + targetRect.width / 2 - tooltipRect.width / 2;
    const left: number = Math.max(8, Math.min(centeredLeft, window.innerWidth - tooltipRect.width - 8));

    tooltip.style.top = `${top}px`;
    tooltip.style.left = `${left}px`;
    tooltip.classList.add('visible');
    tooltip.addEventListener('mouseenter', cancelHideTooltip);
    tooltip.addEventListener('mouseleave', scheduleHideTooltip);

    activeTooltip = tooltip;
}

function scheduleHideTooltip(): void {
    hideTooltipTimeout = setTimeout(hideTooltip, 100);
}

function cancelHideTooltip(): void {
    if (hideTooltipTimeout !== null) {
        clearTimeout(hideTooltipTimeout);
        hideTooltipTimeout = null;
    }
}

function hideTooltip(): void {
    if (activeTooltip !== null) {
        activeTooltip.remove();
        activeTooltip = null;
    }
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
    showTableLoader(tableWrapper);

    try {
        const url: string = getTableUrl(tableWrapper, pageCriteria, sortCriteria, filterCriteria);
        const tableParts: TableParts | null = await getTableParts(url);

        if (tableParts === null) {
            return;
        }

        reloadTableHead(tableWrapper, tableParts.head);
        reloadTableBody(tableWrapper, tableParts.body);

        if (tableParts.pagination) {
            reloadTablePagination(tableWrapper, tableParts.pagination);
        }

        if (tableParts.options) {
            reloadOptions(tableWrapper, tableParts.options);
        }

        saveTableState(tableWrapper.id, getStateFromCriteria(pageCriteria, sortCriteria, filterCriteria));
    } finally {
        hideTableLoader(tableWrapper);
    }
}

function restoreTableState(tableWrapper: TableWrapper): void {
    const state: TableStorageData | null = loadTableState(tableWrapper.id);

    if (state === null) {
        return;
    }

    const pageCriteria: PageCriteria = {page: state.page, pageSize: state.pageSize};
    const sortCriteria: SortCriteria | null = state.sortColumn !== null
        ? {column: state.sortColumn, order: state.sortOrder ?? 'asc', deleteSort: false}
        : null;
    const filterCriteria: FilterCriteria | null = state.filters !== null
        ? {filters: state.filters, deleteFilters: false}
        : null;

    reloadTable(tableWrapper, pageCriteria, sortCriteria, filterCriteria).then((): void => {
        bindFunctions(tableWrapper);
        bindDropdowns(tableWrapper);
    });
}

function getStateFromCriteria(
    pageCriteria: PageCriteria | null,
    sortCriteria: SortCriteria | null,
    filterCriteria: FilterCriteria | null,
): TableStorageData {
    return {
        page: pageCriteria?.page ?? 1,
        pageSize: pageCriteria?.pageSize ?? 10,
        sortColumn: sortCriteria?.deleteSort ? null : (sortCriteria?.column ?? null),
        sortOrder: sortCriteria?.deleteSort ? null : (sortCriteria?.order ?? null),
        filters: filterCriteria?.deleteFilters ? null : (filterCriteria?.filters ?? null),
    };
}

function showTableLoader(tableWrapper: TableWrapper): void {
    const loader: HTMLElement | null = tableWrapper.querySelector('.table-loader');

    if (loader !== null) {
        loader.classList.remove('hidden');
    }
}

function hideTableLoader(tableWrapper: TableWrapper): void {
    const loader: HTMLElement | null = tableWrapper.querySelector('.table-loader');

    if (loader !== null) {
        loader.classList.add('hidden');
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

function getTableParts(url: string): Promise<TableParts | null> {
    return new Promise((resolve: (tableParts: TableParts | null) => void): void => {
        axios.get(url).then(response => {
            resolve(response.data);
        }).catch((): void => {
            console.error('Error while loading table parts');
            resolve(null);
        });
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
        case 'json':
            return getJsonFilterQuery(<JsonFilter> filter, index);
    }
}

function getTextFilterQuery(filter: TextFilter, index: number): string {
    return `${filterKey}[${filter.column}][${index}][value]=${encodeURIComponent(filter.value)}
        &${filterKey}[${filter.column}][${index}][operator]=${encodeURIComponent(filter.filterType)}
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

function getJsonFilterQuery(filter: JsonFilter, index: number): string {
    return `${filterKey}[${filter.column}][${index}][value]=${encodeURIComponent(filter.value)}
        &${filterKey}[${filter.column}][${index}][operator]=${encodeURIComponent(filter.filterType)}
        &${filterKey}[${filter.column}][${index}][jsonPath]=${encodeURIComponent(filter.jsonPath ?? '')}
        &${filterKey}[${filter.column}][${index}][type]=${filter.type}`;
}

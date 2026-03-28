import {getData, urlIncludesGetParameters} from "../helpers/general";
import {bindPagination, getCurrentPage} from "./pagination";
import {bindPageSizeChange, getCurrentPageSize} from "./page-size";
import {bindChecking, checkSavedCheckboxes, handleBulkActions} from "./checkboxes";
import {bindOptions} from "./options";
import {
    ColumnsCriteria,
    DateFilter,
    Filter,
    FilterCriteria,
    JsonFilter,
    NumberFilter,
    PageCriteria,
    SearchCriteria,
    SelectFilter,
    SortCriteria,
    TableParts,
    TableWrapper,
    TextFilter
} from "../interfaces/table";
import {deleteFilterKey, deleteSearchKey, deleteSortKey, filterKey, orderKey, pageKey, pageSizeKey, searchKey, sortKey, visibleColumnsKey} from "./constants";
import {bindInlineActions, bindShowingRowActions, setActionsToDefaultPosition} from "./actions";
import axios from "axios";
import {bindDropdowns} from "../utils/dropdown";
import {bindHeaderSorting, getCurrentOrder, getCurrentSort} from "./sort";
import {bindFilter, getCurrentFilterCriteria} from "./filter";
import {bindSearch, getCurrentSearchCriteria} from "./search";
import {bindColumnVisibility, getCurrentVisibleColumns} from "./columns";
import {loadTableState, saveTableState, TableState} from "./state";

export function bindTableFunctions(tableWrapper: TableWrapper | null = null): void {
    let tables: TableWrapper[] = Array.from(document.querySelectorAll('.pj-table-wrapper'));

    if (tableWrapper !== null) {
        tables = [tableWrapper];
    }

    tables.forEach((table: TableWrapper): void => {
        bindUpdate(table);
        bindFunctions(table);
        restoreStateFromLocalStorage(table);
    });
}

export function dispatchUpdateEvent(tableWrapper: TableWrapper, detail: object = {}): void {
    tableWrapper.dispatchEvent(new CustomEvent('update', {
        detail: detail,
    }));
}

function bindUpdate(tableWrapper: TableWrapper): void {
    tableWrapper.addEventListener('update', function (event: CustomEvent): void {
        if (tableWrapper.hasAttribute('data-loading')) {
            return;
        }

        const pageCriteria: PageCriteria = getPageCriteria(tableWrapper, event);
        const sortCriteria: SortCriteria = getSortCriteria(tableWrapper, event);
        const filterCriteria: FilterCriteria = getFilterCriteria(tableWrapper, event);
        const searchCriteria: SearchCriteria = getSearchCriteria(tableWrapper, event);
        const columnsCriteria: ColumnsCriteria = getColumnsCriteria(tableWrapper, event);

        setActionsToDefaultPosition(tableWrapper);

        saveTableState(
            tableWrapper.id,
            pageCriteria,
            sortCriteria,
            filterCriteria,
            searchCriteria,
            columnsCriteria.visibleColumns,
        );

        reloadTable(tableWrapper, pageCriteria, sortCriteria, filterCriteria, searchCriteria, columnsCriteria).then((): void => {
            bindFunctions(tableWrapper);
            bindDropdowns(tableWrapper);
        });
    });
}

function bindFunctions(tableWrapper: TableWrapper): void {
    bindChecking(tableWrapper);
    bindShowingRowActions(tableWrapper);
    bindInlineActions(tableWrapper);
    bindPagination(tableWrapper);
    bindPageSizeChange(tableWrapper);
    bindOptions(tableWrapper);
    bindHeaderSorting(tableWrapper);
    bindFilter(tableWrapper);
    bindSearch(tableWrapper);
    bindColumnVisibility(tableWrapper);
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

function getSearchCriteria(tableWrapper: TableWrapper, event: CustomEvent): SearchCriteria {
    let searchCriteria: SearchCriteria | undefined = event.detail.searchCriteria;

    if (searchCriteria === undefined) {
        searchCriteria = getCurrentSearchCriteria(tableWrapper);
    }

    return searchCriteria;
}

function getColumnsCriteria(tableWrapper: TableWrapper, event: CustomEvent): ColumnsCriteria {
    const visibleColumns: string[] | undefined = event.detail.visibleColumns;

    if (visibleColumns !== undefined) {
        return {visibleColumns};
    }

    return {visibleColumns: getCurrentVisibleColumns(tableWrapper)};
}

async function reloadTable(
    tableWrapper: TableWrapper,
    pageCriteria: PageCriteria | null = null,
    sortCriteria: SortCriteria | null = null,
    filterCriteria: FilterCriteria | null = null,
    searchCriteria: SearchCriteria | null = null,
    columnsCriteria: ColumnsCriteria | null = null,
): Promise<void> {
    showTableLoader(tableWrapper);

    try {
        const url: string = getTableUrl(tableWrapper, pageCriteria, sortCriteria, filterCriteria, searchCriteria, columnsCriteria);
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
    } finally {
        hideTableLoader(tableWrapper);
    }
}

function showTableLoader(tableWrapper: TableWrapper): void {
    const loader: HTMLElement | null = tableWrapper.querySelector('.table-loader');

    if (loader !== null) {
        loader.classList.remove('hidden');
    }

    tableWrapper.setAttribute('data-loading', 'true');
}

function hideTableLoader(tableWrapper: TableWrapper): void {
    const loader: HTMLElement | null = tableWrapper.querySelector('.table-loader');

    if (loader !== null) {
        loader.classList.add('hidden');
    }

    tableWrapper.removeAttribute('data-loading');
}

function getTableUrl(
    tableWrapper: TableWrapper,
    pageCriteria: PageCriteria | null = null,
    sortCriteria: SortCriteria | null = null,
    filterCriteria: FilterCriteria | null = null,
    searchCriteria: SearchCriteria | null = null,
    columnsCriteria: ColumnsCriteria | null = null,
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

    if (searchCriteria !== null) {
        url = addSearchCriteriaToUrl(url, searchCriteria);
    }

    if (columnsCriteria !== null) {
        url = addColumnsCriteriaToUrl(url, columnsCriteria);
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
    const tablePagination: HTMLElement = tableWrapper.querySelector('.pagination');

    if (tablePagination === null) {
        return;
    }

    tablePagination.outerHTML = pagination;
}

function reloadOptions(tableWrapper: TableWrapper, options: string): void {
    const tableOptions: HTMLElement = tableWrapper.querySelector('.table-options');

    if (tableOptions === null) {
        return;
    }

    const searchInput: HTMLInputElement | null = tableOptions.querySelector('.search-input');
    const searchInputFocused: boolean = searchInput === document.activeElement;
    const selectionStart: number | null = searchInput?.selectionStart ?? null;
    const selectionEnd: number | null = searchInput?.selectionEnd ?? null;

    tableOptions.outerHTML = options;

    if (searchInputFocused) {
        const newSearchInput: HTMLInputElement | null = tableWrapper.querySelector('.search-input');

        if (newSearchInput !== null) {
            newSearchInput.focus();
            newSearchInput.setSelectionRange(selectionStart, selectionEnd);
        }
    }
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

function addSearchCriteriaToUrl(url: string, searchCriteria: SearchCriteria): string {
    urlIncludesGetParameters(url) ? url += '&' : url += '?';

    if (searchCriteria.deleteSearch || searchCriteria.query === null) {
        return url + `${deleteSearchKey}=true`;
    }

    return url + `${searchKey}=${encodeURIComponent(searchCriteria.query)}&${deleteSearchKey}=false`;
}

function addColumnsCriteriaToUrl(url: string, columnsCriteria: ColumnsCriteria): string {
    if (columnsCriteria.visibleColumns === null) {
        return url;
    }

    urlIncludesGetParameters(url) ? url += '&' : url += '?';

    return url + columnsCriteria.visibleColumns
        .map((col: string) => `${visibleColumnsKey}[]=${encodeURIComponent(col)}`)
        .join('&');
}

function restoreColumnVisibilityCheckboxes(tableWrapper: TableWrapper, visibleColumns: string[] | null): void {
    if (visibleColumns === null) {
        return;
    }

    const checkboxes: NodeListOf<HTMLInputElement> = tableWrapper.querySelectorAll('.column-checkbox input[type="checkbox"]');

    checkboxes.forEach((cb: HTMLInputElement): void => {
        cb.checked = visibleColumns.includes(cb.value);
    });
}

function restoreStateFromLocalStorage(tableWrapper: TableWrapper): void {
    if (!tableWrapper.hasAttribute('data-html-parts-url')) {
        tableWrapper.removeAttribute('data-restoring');

        return;
    }

    const state: TableState | null = loadTableState(tableWrapper.id);

    if (state === null) {
        tableWrapper.removeAttribute('data-restoring');

        return;
    }

    const pageCriteria: PageCriteria = {
        page: state.page,
        pageSize: state.pageSize,
    };

    const sortCriteria: SortCriteria = {
        column: state.sort,
        order: state.order,
        deleteSort: state.sort === null,
    };

    const filterCriteria: FilterCriteria = state.filters ?? {
        filters: [],
        deleteFilters: false,
    };

    const searchCriteria: SearchCriteria = {
        query: state.search,
        deleteSearch: state.search === null,
    };

    const columnsCriteria: ColumnsCriteria = {
        visibleColumns: state.visibleColumns,
    };

    reloadTable(tableWrapper, pageCriteria, sortCriteria, filterCriteria, searchCriteria, columnsCriteria).then((): void => {
        tableWrapper.removeAttribute('data-restoring');
        restoreColumnVisibilityCheckboxes(tableWrapper, columnsCriteria.visibleColumns);
        bindFunctions(tableWrapper);
        bindDropdowns(tableWrapper);
    });
}

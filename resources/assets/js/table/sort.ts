import {dispatchUpdateEvent} from "./table";
import {TableWrapper} from "../interfaces/table";
import {getData} from "../helpers/general";

export function bindHeaderSorting(tableWrapper: TableWrapper): void {
    const sortableHeaders: NodeListOf<HTMLElement> = tableWrapper.querySelectorAll('thead th[data-sort-column]');

    sortableHeaders.forEach((th: HTMLElement): void => {
        th.addEventListener('click', function (): void {
            if (tableWrapper.hasAttribute('data-loading')) {
                return;
            }

            const column: string = getData(this, 'sort-column');
            const isSortedAsc: boolean = this.classList.contains('sorted-asc');
            const isSortedDesc: boolean = this.classList.contains('sorted-desc');

            if (isSortedDesc) {
                dispatchUpdateEvent(tableWrapper, {column: '', order: 'asc', deleteSort: true});
                return;
            }

            const newOrder: string = isSortedAsc ? 'desc' : 'asc';
            dispatchUpdateEvent(tableWrapper, {column, order: newOrder});
        });
    });
}

export function getCurrentSort(tableWrapper: TableWrapper): string | null {
    const activeTh: HTMLElement | null = tableWrapper.querySelector('thead th[data-sort-column].sorted-asc, thead th[data-sort-column].sorted-desc');

    if (activeTh === null) {
        return null;
    }

    return getData(activeTh, 'sort-column');
}

export function getCurrentOrder(tableWrapper: TableWrapper): string | null {
    const activeTh: HTMLElement | null = tableWrapper.querySelector('thead th[data-sort-column].sorted-asc, thead th[data-sort-column].sorted-desc');

    if (activeTh === null) {
        return null;
    }

    return activeTh.classList.contains('sorted-asc') ? 'asc' : 'desc';
}
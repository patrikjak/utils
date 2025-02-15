import {dispatchUpdateEvent} from "./table";
import {TableWrapper} from "../interfaces/table";
import {getData} from "../helpers/general";

export function bindSorting(tableWrapper: TableWrapper): void {
    bindChangeOrder(tableWrapper);
    bindDeleteSort(tableWrapper);

    const options: NodeListOf<HTMLElement> = tableWrapper.querySelectorAll('.table-options .sorter-wrapper .option');

    options.forEach((option: HTMLElement): void => {
        option.addEventListener('click', function (): void {
            if (this.classList.contains('selected')) {
                return;
            }

            dispatchUpdateEvent(
                tableWrapper,
                {
                    column: getColumnFromSelectedOption(this),
                    order: 'asc',
                },
            );
        });
    });
}

export function getCurrentSort(tableWrapper: TableWrapper): string | null {
    if (!tableIsSorted(tableWrapper)) {
        return null;
    }

    const sortOption: HTMLElement = tableWrapper.querySelector('.table-options .sort-values .option');

    return getData(sortOption, 'column');
}

export function getCurrentOrder(tableWrapper: TableWrapper): string {
    return null;
}

function getColumnFromSelectedOption(option: HTMLElement): string {
    return getData(option, 'column');
}

function bindChangeOrder(tableWrapper: TableWrapper): void {
    if (!tableIsSorted(tableWrapper)) {
        return;
    }

    const order: HTMLElement = tableWrapper.querySelector('.table-options .sort-values .option .sort-order');

    order.addEventListener('click', function (): void {
        dispatchUpdateEvent(
            tableWrapper,
            {
                column: getCurrentSort(tableWrapper),
                order: getNewOrder(this.closest('.option')),
            },
        );
    });
}

function bindDeleteSort(tableWrapper: TableWrapper): void {
    if (!tableIsSorted(tableWrapper)) {
        return;
    }

    const deleteSort: HTMLElement = tableWrapper.querySelector('.table-options .sort-values .option .close-button');

    deleteSort.addEventListener('click', function (): void {
        dispatchUpdateEvent(
            tableWrapper,
            {
                column: '',
                order: 'asc',
                deleteSort: true,
            },
        );
    });
}

function getNewOrder(orderElement: HTMLElement): string {
    const currentOrder: string = getData(orderElement, 'order');

    return currentOrder === 'asc' ? 'desc' : 'asc';
}

function tableIsSorted(tableWrapper: TableWrapper): boolean {
    const tableOptions: HTMLElement = tableWrapper.querySelector('.table-options');

    if (tableOptions === null) {
        return false;
    }

    const sortValues: HTMLElement = tableOptions.querySelector('.table-options .sort-values');

    return sortValues !== null;
}
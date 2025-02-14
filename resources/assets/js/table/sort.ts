import {dispatchUpdateEvent} from "./table";
import {TableWrapper} from "../interfaces/table";
import {getData} from "../helpers/general";

export function bindSorting(tableWrapper: TableWrapper): void {
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
    return null;
}

export function getCurrentOrder(tableWrapper: TableWrapper): string {
    return null;
}

function getColumnFromSelectedOption(option: HTMLElement): string {
    console.log(getData(option, 'column'));
    return getData(option, 'column');
}
import {getDropdownValue} from "../utils/dropdown";
import {TableWrapper} from "../interfaces/table";
import {dispatchUpdateEvent} from "./table";

export function bindPageSizeChange(tableWrapper: TableWrapper): void {
    const pageSize: HTMLElement = tableWrapper.querySelector('.page-size');

    if (!pageSize) {
        return;
    }

    const dropdown: HTMLDivElement = pageSize.querySelector('.pj-dropdown');

    dropdown.addEventListener('change', async function (): Promise<void> {
        dispatchUpdateEvent(tableWrapper, {
            pageSize: parseInt(getDropdownValue(dropdown)),
        });
    });
}

export function getCurrentPageSize(tableWrapper: TableWrapper): number {
    const pageSize: HTMLElement = tableWrapper.querySelector('.page-size');
    const dropdown: HTMLDivElement = pageSize.querySelector('.pj-dropdown');

    return parseInt(getDropdownValue(dropdown));
}
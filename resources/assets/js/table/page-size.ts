import {findGetParameter, getData} from "../helpers/general";
import {getDropdownValue} from "../helpers/dropdown";
import {getPageQueryForTable, loadPage} from "./pagination";

export function bindPageSizeChange(table: HTMLElement): void {
    const pageSize: HTMLElement = table.querySelector('.page-size');

    if (!pageSize) {
        return;
    }

    const dropdown: HTMLDivElement = pageSize.querySelector('.be-dropdown');

    dropdown.addEventListener('change', async function (): Promise<void> {
        const baseLink = getData(pageSize, 'href');
        const pageKey = getPageQueryForTable(table);
        const page = findGetParameter(pageKey);
        let link = `${baseLink}?pageSize=${getDropdownValue(dropdown)}`;

        if (page !== null && page !== 'null') {
            link += `&${pageKey}=${page}`;
        }

        await loadPage(table, link);
    });
}
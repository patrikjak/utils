import {dispatchUpdateEvent} from "./table";
import {findGetParameter} from "../helpers/general";
import {pageKey} from "./constants";
import {TableWrapper} from "../interfaces/table";

export function bindPagination(tableWrapper: TableWrapper): void {
    const pagination: NodeListOf<HTMLElement> = tableWrapper.querySelectorAll('.pagination');

    pagination.forEach((paginationElement: HTMLElement): void => {
        bindPaginationLinks(tableWrapper, paginationElement);
    });
}

export function getCurrentPage(tableWrapper: TableWrapper): number {
    const activeLink: HTMLElement = tableWrapper.querySelector('.pagination a.active');

    return parseInt(activeLink.querySelector('.page-number').textContent);
}

function bindPaginationLinks(tableWrapper: TableWrapper, paginationElement: HTMLElement): void {
    const links: NodeListOf<HTMLElement> = paginationElement.querySelectorAll('.link');

    links.forEach((link: HTMLElement): void => {
        link.addEventListener('click', async function (e: MouseEvent): Promise<void> {
            e.preventDefault();

            if (!canClickOnPaginationLink(link)) {
                return;
            }

            dispatchUpdateEvent(tableWrapper, {
                page: getPageFromUrl(link.getAttribute('href')),
            });
        });
    });
}

function getPageFromUrl(url: string): number | null {
    const page: string = findGetParameter(pageKey, url);

    if (page === null) {
        return null;
    }

    return parseInt(page);
}

function canClickOnPaginationLink(link: HTMLElement): boolean {
    return !link.classList.contains('active') && !link.classList.contains('disabled');
}
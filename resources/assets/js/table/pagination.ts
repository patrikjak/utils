import axios from "axios";
import {bindTableFunctions} from "./table";
import {findGetParameter, getData, insertAfter} from "../helpers/general";
import {bindDropdowns} from "../utils/dropdown";

export function bindPagination(table: HTMLElement): void {
    if (!table.classList.contains('pj-table-wrapper')) {
        table = table.closest('.pj-table-wrapper');
    }

    const pagination: NodeListOf<HTMLElement> = table.querySelectorAll('.pagination');

    pagination.forEach((paginationElement: HTMLElement): void => {
        const links: NodeListOf<HTMLElement> = paginationElement.querySelectorAll('.link');

        links.forEach((link: HTMLElement): void => {
            link.addEventListener('click', async function (e): Promise<void> {
                e.preventDefault();
                if (canClickOnPaginationLink(link)) {
                    await loadPage(
                        link.closest('.pj-table-wrapper'),
                        link.getAttribute('href'),
                    );
                }
            });
        });
    });
}

export function getPageQueryForTable(table: HTMLElement): string {
    return getData(table.closest('.pj-table-wrapper'), 'page-key');
}

export async function loadPage(table: HTMLElement, link: string): Promise<void> {
    const pageContent = await getPageContent(link);

    setPageToUrl(link, getPageQueryForTable(table));
    setActionsToDefaultPosition(table);

    // @ts-ignore
    loadTableHead(table, pageContent.head);
    // @ts-ignore
    loadTableBody(table, pageContent.body);
    // @ts-ignore
    loadPagination(table, pageContent.pagination);

    bindTableFunctions(table);
    bindDropdowns(table);
}

function getPageContent(link: string): Promise<object> {
    return new Promise(resolve => {
        axios.get(link).then(response => {
            resolve(response.data);
        }).catch((): void => {
            console.error('Error while loading page content');
        });
    });
}

function loadTableHead(table: HTMLElement, pageContent: string): void {
    const tableHead = table.querySelector('thead');
    tableHead.innerHTML = pageContent;
}

function loadTableBody(table: HTMLElement, pageContent: string): void {
    const tableBody = table.querySelector('tbody');
    tableBody.innerHTML = pageContent;
}

function loadPagination(table: HTMLElement, pagination: string): void {
    const tablePagination = table.closest('.pj-table-wrapper').querySelector('.pagination');
    tablePagination.outerHTML = pagination;
}

function canClickOnPaginationLink(link: HTMLElement): boolean {
    return !link.classList.contains('active') && !link.classList.contains('disabled');
}

function setActionsToDefaultPosition(table: HTMLElement): void {
    const actions: HTMLElement = table.querySelector('.table-actions');

    if (actions !== null) {
        insertAfter(actions, table.querySelector('.pj-table'));
    }
}

function setPageToUrl(link: string, pageKey: string): void {
    const pageNumber = findGetParameter(pageKey, link);

    if (pageNumber === null) {
        return;
    }

    const url = new URL(window.location.href);
    url.searchParams.set(pageKey, pageNumber);

    window.history.replaceState({}, '', url.toString());
}
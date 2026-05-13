import {
    Filter,
    FilterCriteria,
    FilterModalResponse,
    TableWrapper,
} from "../interfaces/table";
import axios, {AxiosResponse} from "axios";
import {bindDropdowns} from "../utils/dropdown";
import {filterModalUrl} from "./constants";
import {getBaseUrl, getData, urlIncludesGetParameters} from "../helpers/general";
import notify from "../utils/notification";
import Modal from "../utils/Modal";
import {translator} from "../translator";
import {dispatchUpdateEvent} from "./table";
import {getDropdownValue} from "../utils/dropdown";

let filterModal: Modal | null = null;

export function bindFilter(tableWrapper: TableWrapper): void {
    bindClosingOptions(tableWrapper);
    const options: NodeListOf<HTMLElement> = tableWrapper.querySelectorAll('.table-options .filter-options-wrapper .option');

    options.forEach((option: HTMLElement): void => {
        option.addEventListener('click', async function (): Promise<void> {
            await showFilterModal(this);
            bindFiltering(tableWrapper, this);
        });
    });
}

export function getCurrentFilterCriteria(tableWrapper: TableWrapper): FilterCriteria {
    return {
        filters: getCurrentFilters(tableWrapper),
        deleteFilters: false,
    };
}

function bindClosingOptions(tableWrapper: TableWrapper): void {
    const options: NodeListOf<HTMLElement> = tableWrapper.querySelectorAll('.table-options .filter-values .values .option');

    options.forEach((option: HTMLElement): void => {
        option.querySelector('.close-button').addEventListener('click', function (): void {
            option.remove();

            const optionsCount: number = tableWrapper.querySelectorAll('.table-options .filter-values .values .option').length;

            dispatchUpdateEvent(tableWrapper, {
                page: 1,
                filterCriteria: {
                    filters: getCurrentFilters(tableWrapper),
                    deleteFilters: optionsCount === 0,
                },
            });
        });
    });
}

async function showFilterModal(filterOption: HTMLElement): Promise<void> {
    const type: string = getData(filterOption, 'type');
    let url: string = `${getBaseUrl()}/${filterModalUrl.replace('{type}', type)}`;

    const params: string[] = buildDataParams(filterOption, ['column', 'type']);

    if (params.length > 0) {
        url += (urlIncludesGetParameters(url) ? '&' : '?') + params.join('&');
    }

    const filterForm: FilterModalResponse | void = await axios.get(url)
        .then((response: AxiosResponse): FilterModalResponse | void => {
            return response.data;
        }).catch((): void => {
            notify(translator.t('errors.400.message'), translator.t('errors.400.title'), 'error');
        });

    if (!filterForm) {
        return;
    }

    const modalTitle: string = `${translator.t('table.filter.title')} - ${filterOption.querySelector('span').textContent}`;

    filterModal = new Modal(true);

    filterModal
        .setId('filter-modal')
        .setTitle(modalTitle)
        .setBody(filterForm.modal)
        .setFooterButton(translator.t('table.filter.title'))
        .open();

    await loadSelectOptions();
}

async function loadSelectOptions(): Promise<void> {
    const modal: HTMLElement | null = document.getElementById('filter-modal');

    if (!modal) {
        return;
    }

    const placeholder: HTMLElement | null = modal.querySelector('[data-select-filter-options]');

    if (!placeholder) {
        return;
    }

    const optionsUrl: string | null = getData(placeholder, 'options-url');

    if (!optionsUrl) {
        return;
    }

    const response: AxiosResponse = await axios.get(optionsUrl);
    placeholder.innerHTML = response.data.htmlComponent;

    const filterField: string | null = getData(placeholder, 'filter-field');
    const injectedDropdown: HTMLElement | null = placeholder.querySelector('.pj-dropdown');

    if (filterField && injectedDropdown) {
        injectedDropdown.dataset.filterField = filterField;
        placeholder.removeAttribute('data-filter-field');
    }

    bindDropdowns(placeholder);
}

function bindFiltering(tableWrapper: TableWrapper, option: HTMLElement): void {
    const modal: HTMLElement = document.getElementById('filter-modal');

    modal.querySelector('.footer button').addEventListener('click', function (): void {
        const filter: Filter | null = getFilterFromOption(option, modal);

        if (filter === null) {
            return;
        }

        dispatchUpdateEvent(tableWrapper, {
            page: 1,
            filterCriteria: {
                filters: [...getCurrentFilters(tableWrapper), filter],
                deleteFilters: false,
            },
        });

        filterModal.close();
    });
}

function getFilterFromOption(option: HTMLElement, modal: HTMLElement): Filter | null {
    const type: string = getData(option, 'type');
    const column: string = getData(option, 'column');
    const formInner: HTMLElement | null = modal.querySelector('#filter-form-inner');

    if (!formInner) {
        return null;
    }

    try {
        const data: Record<string, string | number | null> = serializeForm(formInner);

        return {column, type, data};
    } catch (e) {
        console.error(e);
        return null;
    }
}

function getFilterFromFilterValue(filterValue: HTMLElement): Filter | null {
    const type: string = getData(filterValue, 'type');
    const column: string = getData(filterValue, 'column');
    const data: Record<string, string | number | null> = readDataAttributes(filterValue, ['column', 'type']);

    return {column, type, data};
}

function getCurrentFilters(tableWrapper: TableWrapper): Filter[] {
    const filterValues: NodeListOf<HTMLElement> = tableWrapper.querySelectorAll('.table-options .filter-values .values .option');

    const filters: Filter[] = [];

    filterValues.forEach((filterValue: HTMLElement): void => {
        const filter: Filter | null = getFilterFromFilterValue(filterValue);

        if (filter !== null) {
            filters.push(filter);
        }
    });

    return filters;
}

function serializeForm(container: HTMLElement): Record<string, string | number | null> {
    const data: Record<string, string | number | null> = {};

    container.querySelectorAll<HTMLElement>('[data-filter-field]').forEach((el: HTMLElement): void => {
        const field: string = getData(el, 'filter-field');

        if (el.classList.contains('pj-dropdown')) {
            data[field] = getDropdownValue(el);
        } else if (el instanceof HTMLInputElement || el instanceof HTMLSelectElement || el instanceof HTMLTextAreaElement) {
            data[field] = el.value !== '' ? el.value : null;
        }
    });

    return data;
}

function buildDataParams(element: HTMLElement, exclude: string[]): string[] {
    const params: string[] = [];

    Array.from(element.attributes).forEach((attr: Attr): void => {
        if (!attr.name.startsWith('data-')) {
            return;
        }

        const key: string = attr.name.slice(5);

        if (exclude.includes(key)) {
            return;
        }

        params.push(`${encodeURIComponent(key)}=${encodeURIComponent(attr.value)}`);
    });

    return params;
}

function readDataAttributes(element: HTMLElement, exclude: string[]): Record<string, string | number | null> {
    const result: Record<string, string | number | null> = {};

    Array.from(element.attributes).forEach((attr: Attr): void => {
        if (!attr.name.startsWith('data-')) {
            return;
        }

        const key: string = attr.name.slice(5);

        if (exclude.includes(key)) {
            return;
        }

        result[key] = attr.value;
    });

    return result;
}

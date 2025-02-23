import {
    DateFilter,
    Filter,
    FilterCriteria,
    FilterModalResponse,
    NumberFilter,
    SelectFilter,
    TableWrapper,
    TextFilter
} from "../interfaces/table";
import axios, {AxiosResponse} from "axios";
import {filterModalUrl} from "./constants";
import {getData, urlIncludesGetParameters} from "../helpers/general";
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
    let url: string = filterModalUrl.replace('{type}', type);

    if (type === 'date' || type === 'number') {
        const from: string | null = getData(filterOption, 'from');
        const to: string | null = getData(filterOption, 'to');

        if (from !== null) {
            url += `?from=${from}`;
        }

        if (to !== null) {
            url += urlIncludesGetParameters(url) ? `&to=${to}` : `?to=${to}`;
        }
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
    let modalBody: string = filterForm.modal;

    if (type === 'select') {
        modalBody += await getSelectFilterOptions(filterOption);
    }

    filterModal = new Modal(true);

    filterModal
        .setId('filter-modal')
        .setTitle(modalTitle)
        .setBody(modalBody)
        .setFooterButton(translator.t('table.filter.title'))
        .open();
}

async function getSelectFilterOptions(selectedOption: HTMLElement): Promise<string> {
    const url: string = getData(selectedOption, 'options-url');

    let response: AxiosResponse = await axios.get(url);
    return response.data.htmlComponent;
}

function bindFiltering(tableWrapper: TableWrapper, option: HTMLElement): void {
    const modal: HTMLElement = document.getElementById('filter-modal');

    modal.querySelector('.footer button').addEventListener('click', function (): void {
        dispatchUpdateEvent(tableWrapper, {
            filterCriteria: {
                filters: [...getCurrentFilters(tableWrapper), getFilterFromOption(option)],
                deleteFilters: false,
            },
        });

        filterModal.close();
    });
}

function getFilterFromOption(option: HTMLElement): Filter {
    const modal: HTMLElement = document.getElementById('filter-modal');
    const type: string = getData(option, 'type');
    const column: string = getData(option, 'column');

    try {
        switch (type) {
            case 'text':
                return getTextFilter(modal, column);
            case 'number':
                return getNumberFilter(modal, column);
            case 'date':
                return getDateFilter(modal, column);
            case 'select':
                return getSelectFilter(modal, column);
        }
    } catch (e) {
        console.error(e);
    }
}

function getFilterFromFilterValue(filterValue: HTMLElement): Filter {
    const type: string = getData(filterValue, 'type');
    const column: string = getData(filterValue, 'column');

    try {
        switch (type) {
            case 'text':
                return getTextFilterFromFilterValue(filterValue, column);
            case 'number':
                return getNumberFilterFromFilterValue(filterValue, column);
            case 'date':
                return getDateFilterFromFilterValue(filterValue, column);
            case 'select':
                return getSelectFilterFromFilterValue(filterValue, column);
        }
    } catch (e) {
        console.error(e);
    }
}

function getTextFilter(modal: HTMLElement, column: string): Filter {
    const filterType: string = getDropdownValue(modal.querySelector('.pj-dropdown'));
    const valueInput: HTMLInputElement = modal.querySelector('[name="filter_value"]');

    if (!['contains', 'not_contains', 'equals', 'not_equals', 'starts_with', 'ends_with'].includes(filterType)) {
        throw new Error('Invalid filter type');
    }

    return <TextFilter> {
        column,
        type: 'text',
        filterType: filterType,
        value: valueInput.value,
    };
}

function getTextFilterFromFilterValue(filterValue: HTMLElement, column: string): Filter {
    const filterType: string = getData(filterValue, 'operator');
    const value: string = getData(filterValue, 'value');

    return <TextFilter> {
        column,
        type: 'text',
        filterType,
        value,
    };
}

function getNumberFilterFromFilterValue(filterValue: HTMLElement, column: string): Filter {
    let from: string | number = getData(filterValue, 'from');
    let to: string | number = getData(filterValue, 'to');

    if (from !== null) {
        from = parseFloat(from);
    }

    if (to !== null) {
        to = parseFloat(to);
    }

    return <NumberFilter> {
        column,
        type: 'number',
        from,
        to,
    };
}

function getDateFilterFromFilterValue(filterValue: HTMLElement, column: string): Filter {
    const from: string = getData(filterValue, 'from');
    const to: string = getData(filterValue, 'to');

    return <DateFilter> {
        column,
        type: 'date',
        from,
        to,
    };
}

function getSelectFilterFromFilterValue(filterValue: HTMLElement, column: string): Filter {
    const value: string = getData(filterValue, 'value');

    return <SelectFilter> {
        column,
        type: 'select',
        value,
    };
}

function getNumberFilter(modal: HTMLElement, column: string): Filter {
    const valueFromInput: HTMLInputElement = modal.querySelector('[name="filter_value_from"]');
    const valueToInput: HTMLInputElement = modal.querySelector('[name="filter_value_to"]');

    if (valueFromInput.value === '' && valueToInput.value === '') {
        throw new Error('No filter value');
    }

    let from: number | null = parseFloat(valueFromInput.value);
    let to: number | null = parseFloat(valueToInput.value);

    if (valueFromInput.value === '') {
        from = null;
    }

    if (valueToInput.value === '') {
        to = null;
    }

    return <NumberFilter> {
        column,
        type: 'number',
        from,
        to,
    };
}

function getDateFilter(modal: HTMLElement, column: string): Filter {
    const valueFromInput: HTMLInputElement = modal.querySelector('[name="filter_value_from"]');
    const valueToInput: HTMLInputElement = modal.querySelector('[name="filter_value_to"]');

    if (valueFromInput.value === '' && valueToInput.value === '') {
        throw new Error('No filter value');
    }

    let from: string | null = valueFromInput.value;
    let to: string | null = valueToInput.value;

    if (valueFromInput.value === '') {
        from = null;
    }

    if (valueToInput.value === '') {
        to = null;
    }

    return <DateFilter> {
        column,
        type: 'date',
        from,
        to,
    };
}

function getSelectFilter(modal: HTMLElement, column: string): Filter {
    return <SelectFilter> {
        column: column,
        type: 'select',
        value: getDropdownValue(modal.querySelector('.pj-dropdown')),
    };
}

function getCurrentFilters(tableWrapper: TableWrapper): Filter[] {
    const filterValues: NodeListOf<HTMLElement> = tableWrapper.querySelectorAll('.table-options .filter-values .values .option');

    let filters: Filter[] = [];

    filterValues.forEach((filterValue: HTMLElement): void => {
        filters.push(getFilterFromFilterValue(filterValue));
    });

    return filters;
}

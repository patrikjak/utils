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
import {getData} from "../helpers/general";
import notify from "../utils/notification";
import Modal from "../utils/Modal";
import {translator} from "../translator";
import {dispatchUpdateEvent} from "./table";
import {getDropdownValue} from "../utils/dropdown";

let filterModal: Modal | null = null;

export function bindFilter(tableWrapper: TableWrapper): void {
    const options: NodeListOf<HTMLElement> = tableWrapper.querySelectorAll('.table-options .filter-options-wrapper .option');

    options.forEach((option: HTMLElement): void => {
        option.addEventListener('click', async function (): Promise<void> {
            await showFilterModal(this);
            bindFiltering(tableWrapper, this);
        });
    });
}

async function showFilterModal(filterOption: HTMLElement): Promise<void> {
    const type: string = getData(filterOption, 'type');
    const url: string = filterModalUrl.replace('{type}', type);
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
            filterCriteria: [getFilterFromOption(option)],
        });

        filterModal.close();
    });
}

export function getCurrentFilterCriteria(tableWrapper: TableWrapper): FilterCriteria {
    return null;
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

function getTextFilter(modal: HTMLElement, column: string): Filter {
    const filterType: string = getDropdownValue(modal.querySelector('.pj-dropdown'));
    const valueInput: HTMLInputElement = modal.querySelector('[name="filter_value"]');

    if (!['contains', 'equals', 'starts_with', 'ends_with'].includes(filterType)) {
        throw new Error('Invalid filter type');
    }

    return <TextFilter> {
        column,
        type: 'text',
        filterType: filterType,
        value: valueInput.value,
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

function getCurrentFilters() {
    // TODO: Implement
}

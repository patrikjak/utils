import {createElement, getData, removeClassesAfterAnimation, setData, showElement} from "../helpers/general";
import {DEFAULT_ENTER_ANIMATION, DEFAULT_EXIT_ANIMATION} from "../constants";

export function bindDropdowns(scope: Document|HTMLElement = document): void {
    scope.querySelectorAll('.pj-dropdown').forEach(function (dropdown: HTMLDivElement): void {
        adjustDropdownWidth(dropdown);
        bindOpening(dropdown);
        bindSelection(dropdown);

        window.addEventListener('resize', function (): void {
            adjustDropdownWidth(dropdown);
        });
    });
}

export function getDropdownValue(dropdown: HTMLDivElement): string {
    return getData(dropdown.querySelector('.chosen'), 'value');
}

function bindOpening(dropdown: HTMLDivElement): void {
    const arrowWrapper = dropdown.querySelector('.arrow-wrapper');

    arrowWrapper.addEventListener('click', function (): void {
        const arrow = arrowWrapper.querySelector('.arrow');
        const isOpen = arrow.classList.contains('up');

        isOpen ? closeDropdown(dropdown) : openDropdown(dropdown);
    });
}

function bindSelection(dropdown: HTMLDivElement): void {
    const items = dropdown.querySelectorAll('.all-items .pj-dropdown-item');

    items.forEach(function (item: HTMLDivElement): void {
        item.addEventListener('click', function (): void {
            const selected: HTMLElement = dropdown.querySelector('.chosen');
            const currentValue = getData(selected, 'value');
            const newValue = getData(item, 'value');

            selected.querySelector('.label').textContent = item.textContent;
            setData(selected, 'value', newValue);
            closeDropdown(dropdown);

            if (currentValue !== newValue) {
                dropdown.dispatchEvent(new Event('change'));
            }
        });
    });
}

function closeDropdown(dropdown: HTMLDivElement): void {
    const arrow: HTMLElement = dropdown.querySelector('.arrow');
    const items: HTMLElement = dropdown.querySelector('.all-items');

    arrow.classList.add('down');
    arrow.classList.remove('up');
    items.classList.add(DEFAULT_EXIT_ANIMATION);
    removeClassesAfterAnimation(items, [DEFAULT_ENTER_ANIMATION, DEFAULT_EXIT_ANIMATION, 'show']);
}

function openDropdown(dropdown: HTMLDivElement): void {
    const arrow = dropdown.querySelector('.arrow');
    const items = dropdown.querySelector('.all-items');

    arrow.classList.remove('down');
    arrow.classList.add('up');
    items.classList.add(DEFAULT_ENTER_ANIMATION, 'show');
    items.classList.remove(DEFAULT_EXIT_ANIMATION);
}

function adjustDropdownWidth(dropdown: HTMLDivElement): void {
    const hiddenItems: HTMLElement = createElement('div', null, {class: ['hidden-items']});
    const items: NodeListOf<HTMLDivElement> = dropdown.querySelectorAll('.all-items .pj-dropdown-item');

    items.forEach(function (item: HTMLDivElement): void {
        hiddenItems.appendChild(item.cloneNode(true));
    });

    removeHiddenItems();
    showElement(hiddenItems);

    const width = hiddenItems.scrollWidth + 54; // 54 for arrow width and right padding
    const dropdownWrapper: HTMLElement = dropdown.querySelector('.dropdown-wrapper');
    dropdownWrapper.style.width = `${width}px`;
}

function removeHiddenItems(scope: Document|HTMLElement = document): void {
    scope.querySelectorAll('.hidden-items').forEach(function (hiddenItems: HTMLDivElement): void {
        hiddenItems.remove();
    });
}
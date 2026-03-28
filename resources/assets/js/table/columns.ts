import {dispatchUpdateEvent} from "./table";
import {TableWrapper} from "../interfaces/table";

export function bindColumnVisibility(tableWrapper: TableWrapper): void {
    const controller: HTMLElement | null = tableWrapper.querySelector('.column-visibility-controller');

    if (controller === null) {
        return;
    }

    bindToggleOpen(controller);
    bindCheckboxes(tableWrapper, controller);
    bindCloseOnOutsideClick(tableWrapper, controller);
}

export function getCurrentVisibleColumns(tableWrapper: TableWrapper): string[] | null {
    const controller: HTMLElement | null = tableWrapper.querySelector('.column-visibility-controller');

    if (controller === null) {
        return null;
    }

    const checked: NodeListOf<HTMLInputElement> = controller.querySelectorAll('.column-checkbox input[type="checkbox"]:checked');

    return Array.from(checked).map((cb: HTMLInputElement) => cb.value);
}

function bindToggleOpen(controller: HTMLElement): void {
    const clickable: HTMLElement | null = controller.querySelector('.clickable');

    if (clickable === null) {
        return;
    }

    clickable.addEventListener('click', (): void => {
        controller.classList.toggle('opened');
    });
}

function bindCheckboxes(tableWrapper: TableWrapper, controller: HTMLElement): void {
    const checkboxes: NodeListOf<HTMLInputElement> = controller.querySelectorAll('.column-checkbox input[type="checkbox"]');

    checkboxes.forEach((checkbox: HTMLInputElement): void => {
        checkbox.addEventListener('change', (): void => {
            enforceAtLeastOneVisible(controller);
            dispatchUpdateEvent(tableWrapper, {
                visibleColumns: getCheckedValues(controller),
            });
        });
    });
}

function bindCloseOnOutsideClick(tableWrapper: TableWrapper, controller: HTMLElement): void {
    document.addEventListener('click', (event: MouseEvent): void => {
        if (!controller.contains(event.target as Node)) {
            controller.classList.remove('opened');
        }
    });
}

function enforceAtLeastOneVisible(controller: HTMLElement): void {
    const checkboxes: NodeListOf<HTMLInputElement> = controller.querySelectorAll('.column-checkbox input[type="checkbox"]');
    const checkedCount: number = Array.from(checkboxes).filter((cb: HTMLInputElement) => cb.checked).length;

    if (checkedCount === 0) {
        const first: HTMLInputElement | null = controller.querySelector('.column-checkbox input[type="checkbox"]');

        if (first !== null) {
            first.checked = true;
        }
    }

    checkboxes.forEach((cb: HTMLInputElement): void => {
        const isLastChecked: boolean = checkedCount <= 1 && cb.checked;
        cb.disabled = isLastChecked;
    });
}

function getCheckedValues(controller: HTMLElement): string[] {
    const checked: NodeListOf<HTMLInputElement> = controller.querySelectorAll('.column-checkbox input[type="checkbox"]:checked');

    return Array.from(checked).map((cb: HTMLInputElement) => cb.value);
}

import {TableWrapper} from "../interfaces/table";

export function bindOptions(tableWrapper: TableWrapper): void {
    const optionControllers: NodeListOf<HTMLElement> = tableWrapper.querySelectorAll('.table-options .controller');

    optionControllers.forEach((optionController: HTMLElement): void => {
        optionController.addEventListener('click', function (): void {
            openOptions(this);
            bindClosingOptions(this);
        });
    });
}

export function closeOptions(controller: HTMLElement): void {
    controller.classList.remove('opened');
}

function openOptions(optionController: HTMLElement): void {
    optionController.classList.add('opened');
}

export function bindClosingOptions(controller: HTMLElement): void {
    document.addEventListener('click', function close(event: Event): void {
        if (!(event.target instanceof Element)) {
            return;
        }

        if (event.target.closest('.controller') !== null) {
            return;
        }

        closeOptions(controller);
        document.removeEventListener('click', close);
    });
}
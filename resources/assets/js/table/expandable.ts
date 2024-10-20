import {show} from "./expandable/default";

export function bindExpandable(table: HTMLElement): void {
    const template = table.dataset.expandable;
    const rows: NodeListOf<HTMLElement> = table.querySelectorAll('tbody tr');

    rows.forEach((row: HTMLElement): void => {
        row.addEventListener('click', (e: MouseEvent): void => {
            // @ts-ignore
            if (canOpenExtendedData(e.target)) {
                showFunctionMap(template)(row);
            }
        });
    });
}

function showFunctionMap(template: string): (row: HTMLElement) => void {
    const map: any = {
        'default': bindDefaultTemplate,
    };

    return map[template] ?? bindDefaultTemplate;
}

function bindDefaultTemplate(row: HTMLTableRowElement): void {
    show(row);
}

function canOpenExtendedData(elementClicked: HTMLElement): boolean {
    const clickedNotOnCheckbox = elementClicked.closest('.be-checkbox') === null;
    const clickedNotOnActions = (!elementClicked.classList.contains('hellip') &&
        elementClicked.closest('.hellip') === null &&
        !elementClicked.classList.contains('action') && elementClicked.closest('.action') === null);

    return clickedNotOnCheckbox && clickedNotOnActions;
}
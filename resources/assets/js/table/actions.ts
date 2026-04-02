import {getData, insertAfter, isEmpty} from "../helpers/general";
import {TableWrapper} from "../interfaces/table";
import {dispatchUpdateEvent} from "./table";
import axios, {AxiosResponse} from "axios";
import notify from "../utils/notification";

export function bindInlineActions(tableWrapper: TableWrapper): void {
    const inlineButtons: NodeListOf<HTMLElement> = tableWrapper.querySelectorAll('tr td.actions .action-btn');

    inlineButtons.forEach((btn: HTMLElement): void => {
        if (btn.tagName === 'A') {
            return;
        }

        const td: HTMLElement = btn.closest('td');
        const classId: string = btn.classList[1];
        const link: string | null = getData(td, `${classId}-href`);
        const method: string = getData(td, `${classId}-method`) ?? 'get';

        if (link !== null && link !== '') {
            bindActionClick(btn, link, method !== '' ? method : 'get', tableWrapper);
        }
    });
}

export function bindShowingRowActions(tableWrapper: TableWrapper): void {
    if (tableWrapper.querySelector('.table-actions')) {
        const actionButtons: NodeListOf<HTMLElement> = tableWrapper.querySelectorAll('tr td.actions');

        actionButtons.forEach((actionButton: HTMLElement): void => {
            const hellip: HTMLElement | null = actionButton.querySelector('.hellip');

            if (hellip === null) {
                return;
            }

            hellip.addEventListener('click', (): void => {
                showAction(actionButton, tableWrapper.querySelector('.table-actions'));
            });
        });
    }
}

export function setActionsToDefaultPosition(tableWrapper: TableWrapper): void {
    const actions: HTMLElement = tableWrapper.querySelector('.table-actions');

    if (actions !== null) {
        actions.style.display = 'none';
    }
}

export function doAction(tableWrapper: TableWrapper, actionId: string, callback: (rowId: string) => void): void {
    const action: HTMLElement = tableWrapper.querySelector(`.action.${actionId}`);

    action.addEventListener('click', function (): void {
        const tableActions: HTMLElement | null = this.closest('.table-actions');

        if (tableActions !== null) {
            tableActions.style.display = 'none';
        }

        callback(tableWrapper.querySelector('tr.active-actions').id);
    });
}

function renderOffScreenForMeasurement(actions: HTMLElement): void {
    actions.style.visibility = 'hidden';
    actions.style.display = 'inline-block';
    actions.style.position = 'fixed';
}

function showAction(actionButton: HTMLElement, actions: HTMLElement): void {
    renderOffScreenForMeasurement(actions);

    const row = actionButton.closest('tr');
    row.classList.add('active-actions');

    hideHiddenActions(actionButton, actions);
    bindActions(actionButton, actions);

    const actionButtonRect = actionButton.getBoundingClientRect();
    const actionsRect = actions.getBoundingClientRect();

    const leftPosition: number = actionButtonRect.right - actionsRect.width;
    const spaceBelow: number = window.innerHeight - actionButtonRect.bottom;
    const topPosition: number = spaceBelow < actionsRect.height
        ? actionButtonRect.top - actionsRect.height
        : actionButtonRect.bottom;

    actions.style.top = topPosition + 'px';
    actions.style.left = leftPosition + 'px';
    actions.style.visibility = 'visible';

    setTimeout((): void => {
        bindClosingActions(actionButton, actions);
    }, 0);
}

function hideHiddenActions(actionButton: HTMLElement, actions: HTMLElement): void {
    const row = actionButton.closest('tr');
    const hiddenActions = getData(row, 'hidden-actions');
    let explodedHiddenActions: string[] = [];

    if (hiddenActions !== null && hiddenActions !== '') {
        explodedHiddenActions = hiddenActions.split(',');
    }

    const actionElements: NodeListOf<HTMLElement> = actions.querySelectorAll('.action');

    actionElements.forEach((actionElement: HTMLElement): void => {
        if (isEmpty(explodedHiddenActions)) {
            actionElement.style.display = 'flex';

            return;
        }

        for (const hiddenAction of explodedHiddenActions) {
            actionElement.style.display = actionElement.classList.contains(hiddenAction)
                ? 'none'
                : 'flex';
        }
    });
}

function bindActions(actionButton: HTMLElement, actions: HTMLElement): void {
    const actionElements: NodeListOf<HTMLElement> = actions.querySelectorAll('.action');

    actionElements.forEach((actionElement: HTMLElement): void => {
        const classId = actionElement.classList[1];

        const link = getData(actionButton, `${classId}-href`);
        const isLink: boolean = link !== null && link !== '';

        const method = getData(actionButton, `${classId}-method`);
        const differentMethod: boolean = method !== null && method !== '' && method !== 'GET';

        if (isLink) {
            bindActionClick(
                actionElement,
                link,
                differentMethod ? method : 'get',
                actionButton.closest('.pj-table-wrapper'),
            );
        }
    });
}

function bindActionClick(actionElement: HTMLElement, link: string, method: string, tableWrapper: TableWrapper): void {
    const clickHandler = function actionClick(): void {
        if (method !== 'get') {
            axios[method.toLowerCase()](link)
                .then((response: AxiosResponse): void => {
                    const message: string = response.data.message;
                    const title: string = response.data.title ?? 'OK';
                    const level: string = response.data.level ?? 'info';

                    notify(message, title, level);
                })
                .catch((): void => {
                    console.error('Error during action');
                });
        } else {
            window.location.href = link;
        }

        dispatchUpdateEvent(tableWrapper);
    }

    const tableActions = actionElement.closest('.table-actions');

    if (tableActions !== null) {
        removeOnClickListenersFromActions(tableActions, clickHandler);
    }

    actionElement.addEventListener('click', clickHandler);
}

function removeOnClickListenersFromActions(actions: HTMLElement, action: () => void): void {
    actions.addEventListener('close', function (): void {
        const actionElements: NodeListOf<HTMLElement> = actions.querySelectorAll('.action');

        actionElements.forEach((actionElement: HTMLElement): void => {
            actionElement.removeEventListener('click', action);
        });
    });
}

function bindClosingActions(actionButton: HTMLElement, actions: HTMLElement): void {
    document.querySelector('body').addEventListener('click', function bindClosing(e): void {
        if (e.target instanceof Element) {
            const targetIsActionButton: boolean = e.target.classList.contains('hellip') ||
                e.target.closest('.hellip') !== null;

            if (!targetIsActionButton) {
                actions.style.display = 'none';
                setTimeout((): void => {
                    const row = actionButton.closest('tr');
                    row.classList.remove('active-actions');
                }, 100);
            }

            document.querySelector('body').removeEventListener('click', bindClosing);

            actions.dispatchEvent(new Event('close'));
        }
    });
}
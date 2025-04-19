import {getData, insertAfter, isEmpty} from "../helpers/general";
import {TableWrapper} from "../interfaces/table";

export function bindShowingRowActions(tableWrapper: TableWrapper): void {
    if (tableWrapper.querySelector('.table-actions')) {
        const actionButtons: NodeListOf<HTMLElement> = tableWrapper.querySelectorAll('tr td.actions');

        actionButtons.forEach((actionButton: HTMLElement): void => {
            actionButton.querySelector('.hellip').addEventListener('click', (): void => {
                showAction(actionButton, tableWrapper.querySelector('.table-actions'));
            });
        });
    }
}

export function setActionsToDefaultPosition(tableWrapper: TableWrapper): void {
    const actions: HTMLElement = tableWrapper.querySelector('.table-actions');

    if (actions !== null) {
        insertAfter(actions, tableWrapper.querySelector('.pj-table'));
    }
}

export function doAction(tableWrapper: TableWrapper, actionId: string, callback: (rowId: string) => void): void {
    const action: HTMLElement = tableWrapper.querySelector(`.action.${actionId}`);

    action.addEventListener('click', function (): void {
        const tableActions: HTMLElement = this.closest('.table-actions');
        tableActions.style.display = 'none';

        callback(tableWrapper.querySelector('tr.active-actions').id);
    });
}

function showAction(actionButton: HTMLElement, actions: HTMLElement): void {
    actions.style.display = 'inline-block';
    actions.style.position = 'absolute';

    const row = actionButton.closest('tr');
    row.classList.add('active-actions');

    hideHiddenActions(actionButton, actions);

    const tableWrapper: HTMLElement = actionButton.closest('.table-wrapper');
    const visibleWidth: number = tableWrapper.clientWidth;

    const actionButtonPositions = actionButton.getBoundingClientRect();
    const actionsPositions = actions.getBoundingClientRect();
    const tableWrapperPositions = tableWrapper.getBoundingClientRect();

    const actionsNewLeftPosition: number = visibleWidth - actionButtonPositions.width - actionsPositions.width;

    actions.style.top = (actionButtonPositions.top - tableWrapperPositions.top + (actionButtonPositions.height / 2) / 2) + 'px';
    actions.style.left = actionsNewLeftPosition + 'px';

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
        }
    });
}
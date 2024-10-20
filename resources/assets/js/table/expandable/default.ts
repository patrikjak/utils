import {bindClosing, createElement, showElement} from "../../helpers/general";

export function show(row: HTMLElement): void {
    const expandableHtml = row.querySelector('.expandable').innerHTML;
    const expandable = createElement('div', null, {
        class: ['overlay', 'expandable'],
    });

    expandable.innerHTML = expandableHtml;

    showElement(expandable, 'body', 'append', 'flex');
    bindClosing(expandable.querySelector('.close-button'), expandable);
}
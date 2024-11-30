import {dangerSign, infoIcon, successTick, warningSign} from '../components/icons';
import {createElement, showElement, hideElement} from '../helpers/general';

export default function notify(
    message: string,
    heading: string,
    level: string = 'info',
    autoHide: boolean = true,
    autoHideTimeout: number = 5000,
): void {
    const element = notificationElement(message, heading, level);

    showElement(element, 'body', 'append', 'flex', 'animation-slide-in-right');

    if (autoHide) {
        setTimeout((): void => {
            hideElement(element, false, 'animation-slide-out-right');
        }, autoHideTimeout);
    }
}

function getIcon(type: string): string {
    switch (type) {
        case 'success':
            return successTick();
        case 'error':
            return dangerSign();
        case 'warning':
            return warningSign();
        default:
            return infoIcon();
    }
}

function notificationElement(message: string, heading: string, level: string): HTMLElement {
    const element = createElement('div', null, {
        class: ['notification', level],
    });

    let html = '<div class="level">';
    html += getIcon(level);
    html += '</div>';
    html += '<div class="content">';
    html += `<p class="title">${heading}</p>`;
    html += `<p class="message">${message}</p>`;
    html += '</div>';

    element.innerHTML = html;

    return element;
}
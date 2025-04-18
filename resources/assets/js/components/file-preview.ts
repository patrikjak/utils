import {createElement} from "../helpers/general";
import {closeButton} from "./icons";

export function filePreview(image: HTMLElement): HTMLElement {
    const element: HTMLElement = createElement('div', null, {
        class: ['pj-photo-preview'],
    });

    element.appendChild(image);
    element.innerHTML += `<div class="delete-button">${closeButton()}</div>`;

    return element;
}
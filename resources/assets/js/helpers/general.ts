// @ts-nocheck
import {DEFAULT_ENTER_ANIMATION, DEFAULT_EXIT_ANIMATION} from "../constants";
import {loader} from "../components/misc";

const unpairedTags: Array<string> = ['br, hr', 'img', 'input'];

export function isNode(element: string | Node): boolean {
    return (element instanceof Node) && typeof element !== 'string';
}

export function beNode(element: string | Node): Node {
    if (!isNode(element)) {
        element = document.querySelector(element);
    }

    return element;
}

export function isEmpty(variable: any): boolean {
    if (Array.isArray(variable)) {
        return variable.length === 0;
    } else if (isObject(variable)) {
        return Object.keys(variable).length === 0;
    }

    return !!!variable;
}

export function isObject(variable: any): boolean {
    return typeof variable === 'object' && !Array.isArray(variable) && variable !== null;
}

export function getOffset(element: HTMLElement): Object {
    const rect = element.getBoundingClientRect();

    return {
        top: rect.top + window.scrollY,
        left: rect.left + window.scrollX,
    };
}

export function createElement(element:string, textContent = null, attributes = {}): HTMLElement {
    const newElement = document.createElement(element);

    for (let attribute in attributes) {
        if (attribute === 'class') {
            newElement.classList.add(...attributes[attribute]);
        } else if (attribute === 'dataset') {
            for (let dataset in attributes[attribute]) {
                newElement.dataset[dataset] = attributes[attribute][dataset];
            }
        } else {
            newElement.setAttribute(attribute, attributes[attribute]);
        }
    }

    if (textContent !== null && unpairedTags.indexOf(element) === -1) {
        newElement.textContent = textContent;
    }

    return newElement;
}

export function showElement(
    element: HTMLElement | string,
    destination: Element | string = 'body',
    position: string = 'append',
    display: string = 'block',
    animation: string = DEFAULT_ENTER_ANIMATION,
): void {
    destination = beNode(destination);

    if (animation) {
        element.classList.add(animation);
    }

    element.style.display = display;

    if (position === 'prepend') {
        destination.prepend(element);
    } else if (position === 'insertAfter') {
        insertAfter(element, destination);
    } else {
        destination.appendChild(element);
    }
}

export function hideElement(
    element: string | Node,
    all: boolean = false,
    animation: string = DEFAULT_EXIT_ANIMATION,
): void {
    let elements = element;
    let isElementNode = true;

    if (!isNode(element)) {
        isElementNode = false;
        elements = document.querySelectorAll(element);
    }

    if (elements) {
        for (let i = 0; i < (all ? elements.length : 1); i++) {
            element = beNode(elements[i]) ?? elements;

            if (element) {
                toggleAnimationClass(element, animation);
            }
        }

        for (let i = 0; i < (all ? elements.length : 1); i++) {
            if (elements.length || isElementNode) {
                const element = isElementNode ? elements : elements[i];
                setTimeout(() => {
                    element.remove();
                }, 450);
            }
        }
    }
}

export function insertAfter(newNode: HTMLElement, referenceNode: HTMLElement): void {
    referenceNode = beNode(referenceNode);
    referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
}

export function getCss(element: string | Element, property: string = ''): string {
    element = beNode(element);
    const style = getComputedStyle(element);

    if (property === '') {
        return style;
    }

    return style.getPropertyValue(property);
}

export function bindClosing(
    closeButton: HTMLElement,
    toCloseElement: HTMLElement,
    exitAnimation: string = DEFAULT_EXIT_ANIMATION,
): void {
    closeButton.addEventListener('click', () => {
        hideElement(toCloseElement, false, exitAnimation);
    });
}

export function getData(element: HTMLElement, dataAttribute: string): string|null {
    element = beNode(element);
    return element.getAttribute(`data-${dataAttribute}`);
}

export function setData(element: HTMLElement, dataAttribute: string, value): void {
    element = beNode(element);
    element.setAttribute(`data-${dataAttribute}`, value);
}

export function showLoader(element: HTMLElement): void {
    element.classList.add('loading');
    element.innerHTML += loader();
}

export function hideLoader(element: HTMLElement): void {
    element.classList.remove('loading');
    element.querySelector('.loader').remove();
}

export function capitalizeFirstLetter(string: string): string {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

export function findGetParameter(parameterName: string, url: string = window.location.search): string | null {
    if (url === '') {
        return null;
    }

    const urlParts = url.split('?');
    const getParameters = urlParts.length > 1 ? urlParts[1] : urlParts[0];
    const splitParameters = getParameters.split("&");

    for (const parameter of splitParameters) {
        const parameterPair = parameter.split("=");
        if (parameterPair[0] === parameterName) {
            return decodeURIComponent(parameterPair[1]);
        }
    }

    return null;
}

export function getBaseUrl(): string {
    return window.location.origin;
}

export function urlIncludesGetParameters(url: string): boolean {
    return url.includes('?');
}

export function removeClassesAfterAnimation(item: HTMLElement, classes: Array<string>, timeout: number = 200) {
    setTimeout(() => {
        item.classList.remove(...classes);
    }, timeout);
}

function toggleAnimationClass(element: HTMLElement, newAnimationClass: string = DEFAULT_ENTER_ANIMATION): void {
    const oldAnimationRegex = /^animation-.+$/;
    let classToDelete: string = '';

    if (element.classList.value !== '') {
        for (let className of element.classList) {
            let match = oldAnimationRegex.exec(className);

            if (match !== null) {
                classToDelete = match;
                break;
            }
        }
    }

    if (classToDelete !== '') {
        element.classList.remove(classToDelete);
    }

    element.classList.add(newAnimationClass);
}
import {createElement, showElement} from "./general";
import {dangerSign, hiddenEye, shownEye} from "../components/icons";

export function getFormAction(form: HTMLFormElement): string {
    return form.getAttribute('action');
}

export function getFormMethod(form: HTMLFormElement): string {
    return form.getAttribute('method').toLowerCase();
}

export function bindPasswordVisibilitySwitch(): void {
    const passwords: NodeListOf<HTMLFormElement> = document.querySelectorAll('.password');

    for (const password of passwords) {
        const iconContainer: HTMLElement = password.closest('.input').querySelector('.icon');

        iconContainer.addEventListener('click', (): void => {
            togglePasswordVisibility(password, iconContainer);
        });
    }
}

export function emptyFormInputs(form: HTMLFormElement): void {
    const inputs: NodeListOf<HTMLInputElement> = form.querySelectorAll('input');
    const selects: NodeListOf<HTMLSelectElement> = form.querySelectorAll('select');
    const textareas: NodeListOf<HTMLTextAreaElement> = form.querySelectorAll('textarea');

    for (const input of inputs) {
        if (['submit', 'button', 'hidden'].includes(input.type)) {
            continue;
        }

        input.value = '';
    }

    for (const select of selects) {
        select.value = '';
    }

    for (const textarea of textareas) {
        textarea.value = '';
    }
}

export function showErrors(form: HTMLFormElement, errors: object): void {
    hideErrors(form);

    for (const inputName in errors) {
        let input: HTMLInputElement = form.querySelector(`input[name="${inputName}"]`);
        const multiSelectInput: HTMLInputElement = form.querySelector(`input[name="${inputName}[]"]`);
        const textArea: HTMLTextAreaElement = form.querySelector(`textarea[name="${inputName}"]`);
        const select: HTMLSelectElement = form.querySelector(`select[name="${inputName}"]`);

        if (!input) {
            // @ts-ignore
            input = multiSelectInput ?? textArea ?? select;
        }

        if (input) {
            showError(input, errors[inputName]);
        }
    }
}

function showError(input: HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement, error: string): void {
    let wrapper: HTMLElement = input.closest('[class^="be-"]');
    const inputsWrapper = wrapper.closest('[class^="be-"][class$="-wrapper"]');
    const originalInput = input;

    if (inputsWrapper !== null) {
        // @ts-ignore
        wrapper = inputsWrapper;
        input = wrapper.querySelector('[class^="be-"]:last-child');
    }

    const hasIcon = ['text', 'email', 'password', 'tel'].includes(input.type);
    const icon = wrapper.querySelector('.icon');

    wrapper.classList.add('error');

    let destination = input;

    if (icon !== null) {
        destination = input.closest('.input');
    }

    if (['checkbox','radio'].includes(input.type)) {
        destination = input.closest('.group');
    }

    showElement(
        getInputErrorLabel(error),
        destination,
        'insertAfter',
        'flex'
    );

    if (hasIcon === true && icon === null) {
        const currentInputValue = input.value;

        // @ts-ignore
        input.replaceWith(getInputErrorIcon(input));
        wrapper.querySelector('input').value = currentInputValue;
    }

    bindResetInput(document.querySelector(`[name="${originalInput.name}"]`));
}

function bindResetInput(input: HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement): void {
    const inputWrapper = input.closest('.be-input-wrapper');

    if (inputWrapper !== null) {
        const children: NodeListOf<HTMLInputElement> = input.closest('[class^="be-"][class$="wrapper error"]')
            .querySelectorAll('[class^="be-"]');

        if (children) {
            children.forEach((child): void => {
                child.addEventListener('change', (): void => {
                    // @ts-ignore
                    removeError(inputWrapper);
                }, {once: true});
            });
        }

        return;
    }

    input.addEventListener('change', (): void => {
        removeError(input.closest('[class^="be-"]'));
    }, {once: true});
}

function getInputErrorLabel(error: string): HTMLElement {
    const errorElement = createElement('div', null, {
        class: ['error'],
    });

    errorElement.innerHTML = `<p class="message">${error}</p>`;

    return errorElement;
}

function getInputErrorIcon(input: HTMLFormElement): HTMLElement {
    const inputIcon = createElement('div', null, {
        class: ['input'],
    });

    inputIcon.innerHTML = input.outerHTML;
    inputIcon.innerHTML += `<div class="icon error">${dangerSign()}</div>`;

    return inputIcon;
}

function hideErrors(form: HTMLFormElement): void {
    // @ts-ignore
    const errorWrappers: NodeList<HTMLElement> = form.querySelectorAll('[class^="be-"].error');

    errorWrappers.forEach((errorWrapper): void => {
        removeError(errorWrapper);
    });
}

function removeError(inputWrapper: HTMLElement): void {
    inputWrapper.classList.remove('error');
    const errorIcon = inputWrapper.querySelector('.icon.error');

    if (errorIcon !== null) {
        errorIcon.closest('.input').replaceWith(inputWrapper.querySelector('input'));
    }

    const error = inputWrapper.querySelector('div.error');

    if (error !== null) {
        error.remove();
    }
}

function togglePasswordVisibility(passwordInput: HTMLFormElement, iconContainer: HTMLElement): void {
    const shouldShow = passwordInput.classList.contains('hidden');

    passwordInput.classList.remove(shouldShow ? 'hidden' : 'shown');
    passwordInput.classList.add(shouldShow ? 'shown' : 'hidden');
    iconContainer.innerHTML = shouldShow ? hiddenEye() : shownEye();
    passwordInput.type = shouldShow ? 'text' : 'password';
}
import {createElement, hideElement, showElement} from "../helpers/general";
import {closeButton} from "../components/icons";
import {button} from "../components/general";
import Form from "../form/Form";

type callbackTuple = [[string, () => void | boolean]] | [];

export default class Modal {
    public title: string;
    public body: string = '';
    public footer: string = '';
    public id: string | null = null;
    private readonly complex: boolean;
    private buttonId: number = 0;
    private callbacks: callbackTuple = [];

    constructor(complex: boolean = false) {
        this.complex = complex;
    }

    open(): void {
        const modal = this.getModalHTML();
        showElement(modal);
        this.bindClose(modal);
        this.bindCallbacks();
        this.resetModalDefaults();
    }

    close(): void {
        hideElement('body > .overlay');
    }

    resetModalDefaults(): void {
        this.footer = '';
        this.buttonId = 0;
    }

    bindCallbacks(): void {
        for (const tuple of this.callbacks) {
            const [buttonId, callback] = tuple;
            document.querySelector(`#${buttonId}`).addEventListener('click', () => {
                const callbackReturn = callback();
                if (callbackReturn === false) {
                    return;
                }
                this.close();
            });
        }
    }

    getModalHTML(): HTMLElement {
        const classes = ['modal'];
        const modal = createElement('div', null, {
            class: ['overlay'],
        });
        let idAttr = '';

        if (this.complex) {
            classes.push('complex');
        }

        if (this.id !== null) {
            idAttr = ` id="${this.id}"`;
        }

        let modalHTML = `<div class="${classes.join(' ')}"${idAttr}>`;
        modalHTML += closeButton();
        modalHTML += this.getTitleHTML();
        modalHTML += this.getBodyHTML();
        modalHTML += this.getFooterHTML();

        modal.innerHTML = modalHTML;

        return modal;
    }

    bindClose(modal: HTMLElement): void {
        modal.querySelector('.close-button').addEventListener('click', this.close);
        const _this = this;

        document.addEventListener('keydown', function modalClose(e): void {
            if (e.key === 'Escape') {
                _this.close();

                document.removeEventListener('keydown', modalClose);
            }
        });
    }

    getFooterHTML(): string {
        if (this.footer === '') {
            return '';
        }

        return `<div class="footer">${this.footer}</div>`;
    }

    setFooterButton(label: string, callback: () => (void|boolean)|null = null, type: string|null = null): Modal {
        this.buttonId++;
        const buttonId :string = `modal-button-${this.buttonId}`;

        this.footer += button(label, type, buttonId);

        if (callback !== null) {
            this.setCallback(buttonId, callback);
        }

        return this;
    }

    setFooterButtonAsSubmit(label: string, type: string|null = null): Modal {
        this.buttonId++;
        const buttonId :string = `modal-button-${this.buttonId}`;

        this.footer += button(label, type, buttonId);

        this.setCallback(buttonId, function (): void {
            const modalForm: HTMLFormElement = document.querySelector('.modal .body form');
            const form = new Form(modalForm);

            form.submitWithButton(document.querySelector(`.modal #${buttonId}`));
        });

        return this;
    }

    getTitleHTML(): string {
        return `<div class="header"><p${this.complex ? 'class="l"' : ''}>${this.title}</p></div>`;
    }

    setTitle(title: string): Modal {
        this.title = title;

        return this;
    }

    getBodyHTML(): string {
        return `<div class="body">${this.body}</div>`;
    }

    setBody(body: string): Modal {
        this.body = body;

        return this;
    }

    setId(id: string|null = null): Modal {
        this.id = id;

        return this;
    }

    setCallback(buttonId: string, callback: () => (void|boolean)): void {
        // @ts-ignore
        this.callbacks.push([buttonId, callback]);
    }
}
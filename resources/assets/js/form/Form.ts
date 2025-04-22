import {hideLoader, isEmpty, showLoader} from "../helpers/general";
import {sendRequest} from "../helpers/connector";
import {AdditionalData, FormCallback} from "./interfaces/form";
import notify from "../utils/notification";
import {translator} from "../translator";
import {emptyFormInputs, showErrors, getFormAction, getFormMethod} from "./helper";

export default class Form {
    private forms: Array<HTMLFormElement>;
    private successCallback: FormCallback = Form.defaultSuccessCallback;
    private errorCallback: FormCallback = Form.defaultErrorCallback;
    private additionalData: AdditionalData = null;
    private excludedFields: Array<string> = [];
    private recaptchaAction: string | null = null;

    constructor(forms: HTMLFormElement | NodeListOf<HTMLFormElement> | null = null) {
        if (forms === null || forms instanceof NodeList) {
            this.forms = Array.from(document.querySelectorAll('form'));

            return;
        }

        this.forms = [forms];
    }

    public bindSubmit(): Form {
        this.forms.forEach((form) => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                this.submit(form);
            });
        });

        return this;
    }

    public submitWithButton(button: HTMLElement): Form {
        this.forms.forEach((form) => {
            this.submit(form, button);
        });

        return this;
    }

    public setSuccessCallback(callback: FormCallback): Form {
        this.successCallback = callback;

        return this;
    }

    public setErrorCallback(callback: FormCallback): Form {
        this.errorCallback = callback;

        return this;
    }

    public setAdditionalData(data: AdditionalData): Form {
        this.additionalData = data;

        return this;
    }

    public setExcludedFields(fields: Array<string>): Form {
        this.excludedFields = fields;

        return this;
    }

    public setRecaptchaAction(action: string): Form {
        this.recaptchaAction = action;

        return this;
    }

    public static defaultSuccessCallback(form: HTMLFormElement, response: any): void {
        if (form.dataset.redirect !== undefined) {
            window.location.href = form.dataset.redirect;
            return;
        }

        notify(response.message, translator.t('general.super'), 'success');
        emptyFormInputs(form);
    }

    public static defaultErrorCallback(form: HTMLFormElement, response: any): void {
        let errors = response.data?.errors ?? response.response?.data?.errors;

        if ((response?.status === 422 || response?.response?.status === 422) && !isEmpty(errors)) {
            showErrors(form, errors);

            return;
        }

        let message = translator.t('errors.' + response.status + '.message',
            {defaults: [{scope: "errors.default.message"}]}
        );

        if (!isEmpty(response.data?.message)) {
            message = response.data?.message;
        }

        notify(
            message,
            translator.t('errors.' + response.status + '.title',
                {defaults: [{scope: "errors.default.title"}]}
            ),
            'error',
        );
    }

    private submit(form: HTMLFormElement, customSubmitButton: HTMLElement = null): void {
        const submitButton: HTMLElement = customSubmitButton ?? form.querySelector('[type="submit"]');

        if (submitButton) {
            showLoader(submitButton);
        }

        sendRequest(
            getFormAction(form),
            getFormMethod(form),
            this.getData(form),
            this.recaptchaAction,
        ).then(response => {
            this.successCallback(form, response);
        }).catch(error => {
            this.errorCallback(form, error);
        }).finally((): void => {
            if (!submitButton) {
                return;
            }

            hideLoader(submitButton);
        });
    }

    private getData(form: HTMLFormElement): FormData {
        let data = new FormData(form);

        for (const exception of this.excludedFields) {
            data.delete(exception);
        }

        this.addAdditionalDataToForm(data);
        this.transferMultipleValuesToArray(form, data);

        return data;
    }

    private async addAdditionalDataToForm(data: FormData): Promise<void> {
        if (this.additionalData === null) {
            return;
        }

        if (typeof this.additionalData === 'function') {
            this.additionalData = await this.additionalData();

            if (!(this.additionalData instanceof FormData)) {
                return;
            }
        }

        for (const [key, value] of this.additionalData.entries()) {
            data.append(key, value);
        }
    }

    private transferMultipleValuesToArray(form: HTMLFormElement, data: FormData): void {
        let arrays = {};

        for (const [key] of data.entries()) {
            const allValues = data.getAll(key);
            // @ts-ignore
            const isMultiple = form.querySelector(`[name="${key}"]`)?.multiple;

            if (Array.isArray(allValues) && isMultiple) {
                arrays[key] = allValues;
            }
        }

        for (let key in arrays) {
            for (let value of arrays[key]) {
                data.append(`${key}[]`, value);
            }
        }
    }
}
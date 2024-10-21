export type AdditionalData = (() => FormData) | null | FormData;
export type FormCallback = (form: HTMLFormElement, response: any) => void;
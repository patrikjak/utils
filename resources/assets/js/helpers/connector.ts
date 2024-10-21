import axios from "axios";
import {RECAPTCHA_SITE_KEY} from "../constants";

export async function sendRequest(url: string, method: string, data: FormData, recaptchaAction: string|null = null) {
    if (recaptchaAction) {
        const recaptchaToken: string = await getRecaptchaToken(recaptchaAction);
        data.append('recaptchaToken', recaptchaToken);
    }

    return new Promise((resolve, reject): void => {
        axios[method](url, data).then(response => {
            resolve(response.data);
        }).catch(error => {
            if (error.code === 'ERR_NETWORK') {
                reject({status: 407});
                return;
            }

            reject(error.response);
        });
    });
}

function getRecaptchaToken(action: string = 'submit'): Promise<string> {
    return new Promise(resolve => {
        // @ts-ignore
        grecaptcha.ready(function(): void {
            // @ts-ignore
            grecaptcha.execute(RECAPTCHA_SITE_KEY, {action: action}).then(function(token): void {
                resolve(token);
            });
        });
    });
}
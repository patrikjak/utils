import {I18n} from "i18n-js";

const locale = document.querySelector('html').lang;
// @ts-ignore
const translations = await import(`./lang/${locale}.json`);
const i18nTranslations = {};

i18nTranslations[locale] = translations.default;

const i18n = new I18n(i18nTranslations);

i18n.locale = locale;
i18n.defaultLocale = 'sk';

export {i18n as translator};
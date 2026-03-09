import {I18n} from "i18n-js";
import en from './lang/en.json';
import sk from './lang/sk.json';

const locale = document.documentElement.lang;

const i18n = new I18n({en, sk});

i18n.locale = locale;
i18n.defaultLocale = 'sk';

export {i18n as translator};

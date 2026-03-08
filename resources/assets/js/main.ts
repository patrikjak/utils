import Form from './form/Form';
import notify from './utils/notification';
import {getData} from './helpers/general';
import {bindPasswordVisibilitySwitch} from './form/helper';
import {bindTableFunctions} from './table/table';
import {bindDropdowns} from './utils/dropdown';
import {bindUploaders} from './utils/file-uploader';

declare global {
    interface Window {
        PjUtils: {
            Form: typeof Form;
            notify: typeof notify;
            getData: typeof getData;
        };
    }
}

window.PjUtils = {Form, notify, getData};

bindPasswordVisibilitySwitch();
bindTableFunctions();
bindDropdowns();
bindUploaders();

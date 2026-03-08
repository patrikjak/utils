import Form from './form/Form';
import notify from './utils/notification';
import Modal from './utils/Modal';
import {getData} from './helpers/general';
import {doAction} from './table/actions';
import {bindPasswordVisibilitySwitch} from './form/helper';
import {bindTableFunctions} from './table/table';
import {bindDropdowns} from './utils/dropdown';
import {bindUploaders} from './utils/file-uploader';
import {bindAccordions} from './components/accordion';
import {bindTabs} from './components/tabs';
import {bindClipboard} from './components/clipboard';
import {bindNumberInputs} from './form/number';
import {bindComboboxes} from './form/combobox';
import {bindTagsInputs} from './form/tags';
import {bindAlerts} from './components/alert';
import {bindRepeaters} from './form/repeater';

declare global {
    interface Window {
        pjutils: {
            Form: typeof Form;
            notify: typeof notify;
            Modal: typeof Modal;
            getData: typeof getData;
            doAction: typeof doAction;
        };
    }
}

window.pjutils = {Form, notify, Modal, getData, doAction};

bindPasswordVisibilitySwitch();
bindTableFunctions();
bindDropdowns();
bindUploaders();
bindAccordions();
bindTabs();
bindClipboard();
bindNumberInputs();
bindComboboxes();
bindTagsInputs();
bindAlerts();
bindRepeaters();

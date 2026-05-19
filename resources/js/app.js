import './bootstrap';

// FLATPICKR
import flatpickr from "flatpickr";
import { Italian } from "flatpickr/dist/l10n/it.js";
import "flatpickr/dist/flatpickr.css";
window.flatpickr = flatpickr;
flatpickr.localize(Italian);

// INTERNATIONAL TELEPHONE INPUT
import intlTelInput from 'intl-tel-input';
import 'intl-tel-input/styles';
import * as utils from 'intl-tel-input/dist/js/utils.js';
import it from 'intl-tel-input/dist/js/i18n/it.js';
window.intlTelInput = intlTelInput;
window.itiUtils = utils;
window.itiI18nIt = it.default || it;

// SWEET ALERT 2
import './bootstrap';
import Swal from 'sweetalert2';
window.Swal = Swal;

// MY JS
import "./main";
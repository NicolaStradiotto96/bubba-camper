import './bootstrap';
import "./main";

// FLATPICKR
import flatpickr from "flatpickr";
import { Italian } from "flatpickr/dist/l10n/it.js";
import "flatpickr/dist/flatpickr.css";
window.flatpickr = flatpickr;
flatpickr.localize(Italian);
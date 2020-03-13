require('./bootstrap');

// jQuery-UI
import 'jquery-ui/ui/widgets/datepicker.js';
import 'webpack-jquery-ui/autocomplete.js';
import 'webpack-jquery-ui/tooltip.js';
import 'webpack-jquery-ui/selectmenu.js';
import 'webpack-jquery-ui/effects.js';

// JavaScript Plug-ins
require('jquery-validation/dist/jquery.validate');
require('@curveballerpacks/tablesorter');
require('jquery-confirm');

// Custom JavaScript
require('./combobox');
require('./form');
require('./alert');
// require('./datepicker');

window.Papa = require('papaparse/papaparse');
window.html2canvas = require('html2canvas/dist/html2canvas.min');

// require('dotenv').config();
// const env = process.env.APP_ENV;

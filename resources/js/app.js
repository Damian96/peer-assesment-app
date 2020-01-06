require('./bootstrap');

// Bootstrap 4.0
// jQuery 3.3
import $ from 'jquery';
// jQuery-UI
import 'jquery-ui/ui/widgets/datepicker.js';
import 'webpack-jquery-ui/autocomplete.js';
import 'webpack-jquery-ui/tooltip.js';
import 'webpack-jquery-ui/selectmenu.js';
import 'webpack-jquery-ui/effects.js';

window.$ = window.jQuery = $;
window.Papa = require('papaparse/papaparse');

require('popper.js');
require('bootstrap');

require('jquery-validation/dist/jquery.validate');
require('@curveballerpacks/tablesorter');
require('jquery-confirm');

require('./combobox');
require('./form');

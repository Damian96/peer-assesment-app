require('./bootstrap');

// Bootstrap 4.0
import $ from 'jquery';
import 'jquery-ui/ui/widgets/datepicker.js'; //add as many widget as you may need

window.$ = window.jQuery = $;
window.Papa = require('papaparse/papaparse');

require('popper.js');
require('bootstrap');

require('jquery-validation/dist/jquery.validate');
require('@curveballerpacks/tablesorter');
require('jquery-confirm');

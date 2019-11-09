require('./bootstrap');

// Bootstrap 4.0
import $ from 'jquery';
import 'jquery-ui/ui/widgets/datepicker.js'; //add as many widget as you may need

window.$ = window.jQuery = $;
require('popper.js');
require('bootstrap');
require('jquery-confirm');
require('@curveballerpacks/tablesorter');

require('./jquery.parse.min.js');

require('jquery-validation/dist/jquery.validate');

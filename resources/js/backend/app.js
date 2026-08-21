import 'alpinejs'



import ApexCharts from 'apexcharts';
import 'apexcharts/unit';
import { droplet } from 'apexcharts/unit-shapes';
import { tree } from 'apexcharts/unit-shapes';

window.ApexCharts = ApexCharts;
window.droplet = droplet;
window.tree = tree;


window.$ = window.jQuery = require('jquery');
window.Swal = require('sweetalert2');

// CoreUI
require('@coreui/coreui');

// SJ
require('../plugins');

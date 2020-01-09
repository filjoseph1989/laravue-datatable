require('./bootstrap');

window.Vue = require('vue');

import UserDataTableComponent from './components/UserDataTable.vue';

Vue.component('user-data-table', UserDataTableComponent);

import swal from 'sweetalert2';
window.swal = swal;

var app = new Vue({
    el: '#app'
});

require('./bootstrap');

window.Vue = require('vue');

import UserDataTableComponent from './components/UserDataTable.vue';

Vue.component('user-data-table', UserDataTableComponent);

var app = new Vue({
    el: '#app'
});

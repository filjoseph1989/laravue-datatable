require('./bootstrap');

window.Vue = require('vue');

import { VuejsDatatableFactory } from 'vuejs-datatable';
import UserDataTableComponent from './components/UserDataTable.vue';

Vue.use(VuejsDatatableFactory);
Vue.component('user-data-table', UserDataTableComponent);

var app = new Vue({
    el: '#app'
});

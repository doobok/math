require('./../bootstrap');

// Vue
window.Vue = require('vue');

// import vuetify from './plugins/vuetify' // path to vuetify export

import Vuetify from 'vuetify';
   Vue.use(Vuetify);

// Vuex
// import store from './store/index';

// // валидатор форм
// import Vuelidate from 'vuelidate';
// Vue.use(Vuelidate);

// Components
Vue.component('calendar-component', require('./components/CalendarComponent.vue').default);
Vue.component('classroom-table', require('./components/ClassroomsTable.vue').default);



const app = new Vue({
   el: '#app',
   vuetify: new Vuetify(),
   // store
});

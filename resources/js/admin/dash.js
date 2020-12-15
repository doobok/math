require('./../bootstrap');

// Vue
window.Vue = require('vue');

import Vuetify from 'vuetify';
   Vue.use(Vuetify);

// Vuex
import store from './store/index';

// // валидатор форм
// import Vuelidate from 'vuelidate';
// Vue.use(Vuelidate);

// Components
Vue.component('calendar-component', require('./components/CalendarComponent.vue').default);
Vue.component('classroom-table', require('./components/ClassroomsTable.vue').default);
Vue.component('tutor-table', require('./components/TutorsTable.vue').default);
Vue.component('student-table', require('./components/StudentsTable.vue').default);



const app = new Vue({
   el: '#app',
   vuetify: new Vuetify(),
   store
});

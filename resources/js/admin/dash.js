require('./../bootstrap');

// Vue
window.Vue = require('vue');

import Vuetify from 'vuetify';
   Vue.use(Vuetify);

// vee-validate
import { ValidationProvider, ValidationObserver, extend } from 'vee-validate';
import { required, integer, alpha, alpha_num, min, is, regex } from 'vee-validate/dist/rules';

extend('required', {
  ...required,
  message: 'Це обовʼязкове поле'
});
extend('integer', {
    ...integer,
    message: 'Поле повинне містити ціле число',
  })
extend('min', {
    ...min,
    message: 'Поле повинне містити не менше {length} символів',
  })
extend('alpha', {
    ...alpha,
    message: 'Поле повинне містити виключно літери',
  })
extend('is', {
    ...is,
    message: 'Поля повинні збігатися',
  })
extend('alpha_num', {
    ...alpha_num,
    message: 'Поле повинне містити виключно літери та цифри',
  })
extend('regex', {
    ...regex,
    message: 'обовʼязково 1 цифра 1 мала літера латинського алфавіту та 1 велика',
  })
// Vuex
import store from './store/index';

// // валидатор форм
// import Vuelidate from 'vuelidate';
// Vue.use(Vuelidate);

// Components
Vue.component('calendar-component', require('./components/CalendarComponent.vue').default);
Vue.component('classroom-table', require('./components/ClassroomsTable.vue').default);
Vue.component('tutor-table', require('./components/TutorsTable.vue').default);
Vue.component('tutor-page', require('./components/TutorPage.vue').default);
Vue.component('student-table', require('./components/StudentsTable.vue').default);
Vue.component('student-page', require('./components/StudentPage.vue').default);
Vue.component('finance-table', require('./components/FinanceTable.vue').default);
Vue.component('reports-table', require('./components/ReportsTable.vue').default);
Vue.component('invites-table', require('./components/InvitesTable.vue').default);
Vue.component('users-table', require('./components/UsersTable.vue').default);
Vue.component('settings-table', require('./components/SettingsTable.vue').default);
Vue.component('telegram-webhook', require('./components/TelegramWebhook.vue').default);

Vue.component('register-card', require('./components/auth/RegisterCard.vue').default);

Vue.component('validation-provider', ValidationProvider);
Vue.component('validation-observer', ValidationObserver);



const app = new Vue({
   el: '#app',
   vuetify: new Vuetify(),
   store
});

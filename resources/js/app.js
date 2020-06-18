require('./bootstrap');

// Vue
window.Vue = require('vue');

// Vuex
import store from './store/index';

// валидатор форм
import Vuelidate from 'vuelidate';
Vue.use(Vuelidate);

// Components
Vue.component('button-component', require('./components/ButtonComponent.vue').default);
Vue.component('lead-form-phone', require('./components/LeadFormPhone.vue').default);


const app = new Vue({
   el: '#app',
   store
});

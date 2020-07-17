require('./bootstrap');

// Vue
window.Vue = require('vue');

// Vuex
import store from './store/index';

// мультиязычность
import './ml';

// валидатор форм
import Vuelidate from 'vuelidate';
Vue.use(Vuelidate);

// Components
Vue.component('button-component', require('./components/ButtonComponent.vue').default);
Vue.component('button-course-component', require('./components/ButtonCourseComponent.vue').default);
Vue.component('lead-form-phone', require('./components/LeadFormPhone.vue').default);
Vue.component('star-rating', require('./components/StarRating.vue').default);


const app = new Vue({
   el: '#app',
   store
});

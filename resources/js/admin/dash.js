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
Vue.component('test-component', require('./components/TestComponent.vue').default);
Vue.component('test-component2', require('./components/TestComponent2.vue').default);
Vue.component('test-component3', require('./components/TestComponent3.vue').default);



const app = new Vue({
   el: '#app',
   vuetify: new Vuetify(),
   // store
});

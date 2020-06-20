import Vue from 'vue';
import Vuex from 'vuex';
import Axios from 'axios';

import leads from './leads'

Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    leads
  }
})

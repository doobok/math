import Vue from 'vue';
import Vuex from 'vuex';
import Axios from 'axios';

import lessons from './lessons'

Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    lessons
  }
})

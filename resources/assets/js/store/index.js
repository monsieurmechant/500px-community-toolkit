import Vue from 'vue';
import Vuex from 'vuex';
import photos from './modules/photos';

Vue.use(Vuex);


export default new Vuex.Store({
  modules: {
    photos,
  },
  strict: false,
});

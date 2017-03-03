import Vue from 'vue';
import Vuex from 'vuex';
import commentsByMedia from './modules/comments-by-medias';

Vue.use(Vuex);


export default new Vuex.Store({
  modules: {
    commentsByMedia,
  },
  strict: false,
});

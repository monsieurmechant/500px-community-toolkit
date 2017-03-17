import Vue from 'vue';
import Vuex from 'vuex';
import commentsByMedia from './modules/comments-by-medias';
import commentsByUser from './modules/comments-by-user';

Vue.use(Vuex);


export default new Vuex.Store({
  modules: {
    commentsByMedia,
    commentsByUser,
  },
  strict: false,
});

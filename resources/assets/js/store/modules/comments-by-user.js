/* eslint-disable no-shadow */
import {
  REQUEST_COMMENTS_BY_USER,
  GET_COMMENTS_BY_USER_SUCCESS,
  GET_COMMENTS_BY_USER_FAILURE,
} from './../mutation-types';
import Axios from 'axios';

const state = {
  isFetching: false,
  loaded: false,
  comments: [],
  error: null,
};

export const mutations = {
  [REQUEST_COMMENTS_BY_USER](state) {
    state.isFetching = true;
  },
  [GET_COMMENTS_BY_USER_SUCCESS](state, { comments }) {
    state.error = null;
    if (Array.isArray(comments.data)) {
      state.comments = comments.data;
    }
    state.loaded = true;
    state.isFetching = false;
  },
  [GET_COMMENTS_BY_USER_FAILURE](state, { data }) {
    state.error = data.error;
    state.isFetching = false;
  },
};

export const actions = {
  /**
   * Requests the photos from
   * the /photos API endpoint
   */
  getCommentsByUser({ commit }, followerId) {
    commit(REQUEST_COMMENTS_BY_USER);
    return new Promise((resolve, reject) => {
      Axios.get('/internal/comments', {
        params: {
          follower_id: followerId,
        },
      }).then(response => {
        commit(GET_COMMENTS_BY_USER_SUCCESS, { comments: response.data });
        resolve(response.data);
      }).catch(response => {
        commit(GET_COMMENTS_BY_USER_FAILURE, response.data);
        reject(response.data);
      });
    });
  },

  /**
   * Makes a PUT request to the API to update an entry.
   * If successful the state will also be updated.
   * The error will be returned in a promise.
   */
  // updatePhoto({ state, dispatch }, { id, data }) {
  //   return new Promise((resolve, reject) => {
  //     api.updatePhoto(id, data).then(response => {
  //       dispatch('photoUpdated', { id, data });
  //       resolve(response.data);
  //     }, response => {
  //       reject(response.data);
  //     });
  //   });
  // },
  /**
   * Updates the state when a photo is updated.
   */
  // photoUpdated: ({ commit }, { id, data }) => commit(UPDATE_PHOTO_SUCCESS, { id, data }),
};

export const getters = {
  /**
   * Is the state currently in the process of
   * fetching photos from
   * the Back-End API
   *
   * @param state
   * @return bool
   */
  isFetchingUserComments: state => state.isFetching,

  /**
   * Was the state already fetched from the API
   *
   * @param state
   * @return bool
   */
  userCommentsLoaded: state => state.loaded,

  /**
   * Returns the list of photos.
   * Can filter photos by
   * consultant.
   *
   * @param state
   * @return object
   */
  userCommentsList: state => state.comments,
};

export default {
  state,
  mutations,
  actions,
  getters,
};

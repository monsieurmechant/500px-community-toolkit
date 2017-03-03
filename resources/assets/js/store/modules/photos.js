/* eslint-disable no-shadow */
import {
  REQUEST_PHOTOS,
  GET_PHOTOS_SUCCESS,
  GET_MORE_PHOTOS_SUCCESS,
  GET_PHOTOS_FAILURE,
  UPDATE_PHOTO_SUCCESS,
  SET_TO_PHOTOS_WITH_UNREAD_COMMENTS,
  SET_TO_REGULAR_PHOTOS_LIST,
  ADD_INCLUDE,
  REMOVE_INCLUDE,
} from './../mutation-types';
import Axios from 'axios';

const state = {
  isFetching: false,
  photos: [],
  cursor: {
    previous: '',
    next: '',
    current: '',
  },
  includes: [],
  unread_comments: false,
  hasMore: true,
  error: null,
};

export const mutations = {
  [REQUEST_PHOTOS](state) {
    state.isFetching = true;
  },
  [GET_PHOTOS_SUCCESS](state, { photos }) {
    state.error = null;
    if (Array.isArray(photos.data)) {
      state.photos = photos.data;
    }
    if (photos.hasOwnProperty('meta')) {
      state.cursor = {
        previous: photos.meta.cursor.prev,
        next: photos.meta.cursor.next,
        current: photos.meta.cursor.current,
      };
      state.hasMore = photos.meta.cursor.count >= 50;
    }
    state.isFetching = false;
  },
  [GET_MORE_PHOTOS_SUCCESS](state, { photos }) {
    state.error = null;
    state.photos.push(...photos.data);
    state.cursor = {
      previous: photos.meta.cursor.prev,
      next: photos.meta.cursor.next,
      current: photos.meta.cursor.current,
    };
    state.hasMore = photos.meta.cursor.count >= 50;
    state.isFetching = false;
  },
  [GET_PHOTOS_FAILURE](state, { data }) {
    state.error = data.error;
    state.isFetching = false;
  },
  [SET_TO_PHOTOS_WITH_UNREAD_COMMENTS](state) {
    state.unread_comments = 1;
  },
  [SET_TO_REGULAR_PHOTOS_LIST](state) {
    state.unread_comments = 0;
  },
  [ADD_INCLUDE](state, include) {
    state.includes = [
      ...state.includes,
      include
    ];
  },
  [REMOVE_INCLUDE](state, include) {
    state.includes = state.includes.filter((v) => {
      return v !== include;
    });
  },
};

export const actions = {
  /**
   * Requests the photos from
   * the /photos API endpoint
   */
  getPhotos({ state, commit }) {
    commit(REQUEST_PHOTOS);
    return new Promise((resolve, reject) => {
      Axios.get('/internal/photos', {
        params: {
          'unread_comments': state.unread_comments ? 1 : 0,
          includes: state.includes,
        },
      }).then(response => {
        commit(GET_PHOTOS_SUCCESS, { photos: response.data });
        resolve(response.data);
      }).catch(response => {
        commit(GET_PHOTOS_FAILURE, response.data);
        reject(response.data);
      });
    });
  },
  /**
   * Requests the next page of photos from
   * the /photos API endpoint, using the
   * cursors received in a
   * previous call
   */
  getMorePhotos({ state, commit }) {
    commit(REQUEST_PHOTOS);
    return new Promise((resolve, reject) => {
      Axios.get('/internal/photos', {
        params: {
          unread_comments: state.unread_comments ? 1 : 0,
          cursor: state.cursor.next,
          previous: state.cursor.current,
          includes: state.includes,
        },
      }).then(response => {
        commit(GET_MORE_PHOTOS_SUCCESS, { photos: response.data });
        resolve(response.data);
      }).catch(response => {
        commit(GET_PHOTOS_FAILURE, response.data);
        reject(response.data);
      });
    });
  },
  setToPhotosWithUnreadComments({ commit }) {
    commit(SET_TO_PHOTOS_WITH_UNREAD_COMMENTS);
  },
  setToRegularPhotosList({ commit }) {
    commit(SET_TO_REGULAR_PHOTOS_LIST);
  },
  addInclude({ commit }, include) {
    commit(ADD_INCLUDE, include);
  },
  removeInclude({ commit }, include) {
    commit(REMOVE_INCLUDE, include);
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
  isFetchingPhotos: state => state.isFetching,

  /**
   * Returns the list of photos.
   * Can filter photos by
   * consultant.
   *
   * @param state
   * @return object
   */
  photosList: state => state.photos,

  /**
   * Are there more photos to fetch from the API.
   * The API returns 50 photos / page.
   * We consider that there are no
   * more photos to load if the
   * API returned less than
   * 50 photos on the
   * last call.
   *
   * @param state
   * @return object
   */
  hasMorePhotos: state => state.hasMore,
};

export default {
  state,
  mutations,
  actions,
  getters,
};

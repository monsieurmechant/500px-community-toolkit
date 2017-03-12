import {
  REQUEST_COMMENTS_BY_MEDIAS,
  GET_COMMENTS_BY_MEDIAS_SUCCESS,
  GET_MORE_COMMENTS_BY_MEDIAS_SUCCESS,
  GET_COMMENTS_BY_MEDIAS_FAILURE,
  GET_NEW_COMMENTS_BY_MEDIAS_SUCCESS,
  GET_NEW_PHOTO_COMMENTS_SUCCESS,
  MARK_COMMENT_READ_SUCCESS,
  MARK_ALL_COMMENT_READ_SUCCESS,
  POSTING_REPLY,
  REPLY_TO_COMMENT_SUCCESS,
} from './../mutation-types';
import Axios from 'axios';

import moment from 'moment';

const state = {
  isFetching: false,
  loaded: false,
  photos: [],
  cursor: {
    previous: '',
    next: '',
    current: '',
  },
  hasMore: true,
  error: null,
};

export const mutations = {
  [REQUEST_COMMENTS_BY_MEDIAS](state) {
    state.isFetching = true;
  },
  [GET_COMMENTS_BY_MEDIAS_SUCCESS](state, { photos }) {
    state.error = null;
    if (Array.isArray(photos.data)) {
      state.photos = photos.data.map(p => {
        p.comments.data = p.comments.data.map(c => {
          c.posting_reply = false;
          return c;
        });
        return p;
      });
    }
    if (photos.hasOwnProperty('meta')) {
      state.cursor = {
        previous: photos.meta.cursor.prev,
        next: photos.meta.cursor.next,
        current: photos.meta.cursor.current,
      };
      state.hasMore = photos.meta.cursor.count >= 50;
    }
    state.loaded = true;
    state.isFetching = false;
  },
  [GET_NEW_COMMENTS_BY_MEDIAS_SUCCESS](state, { photos }) {
    if (Array.isArray(photos.data)) {
      photos = photos.data.map(p => {
        p.comments.data = p.comments.data.map(c => {
          c.posting_reply = false;
          return c;
        });
        return p;
      });
      state.photos.unshift(...photos);
    }
  },
  [GET_MORE_COMMENTS_BY_MEDIAS_SUCCESS](state, { photos }) {
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
  [GET_COMMENTS_BY_MEDIAS_FAILURE](state, { data }) {
    state.error = data.error;
    state.isFetching = false;
  },
  [MARK_COMMENT_READ_SUCCESS](state, { commentId, photoId }) {
    const pIndex = state.photos.findIndex(p => p.id === photoId);
    let cIndex = state.photos[pIndex].comments.data.findIndex(c => c.id === commentId);

    const parentId = state.photos[pIndex].comments.data[cIndex].parent_id
    if (parentId === null) {
      return state.photos[pIndex].comments.data[cIndex].read = true;
      ;
    }

    cIndex = state.photos[pIndex].comments.data.findIndex(c => c.id === parentId);

    const chIndex = state.photos[pIndex].comments.data[cIndex].children.data.findIndex(
        c => c.id === commentId
    );

    return state.photos[pIndex].comments.data[cIndex].children.data[chIndex].read = true;
  },
  [MARK_ALL_COMMENT_READ_SUCCESS](state, { photoId }) {
    state.photos = state.photos.filter(p => p.id !== photoId);
  },
  [POSTING_REPLY](state, commentId) {
    let cIndex;
    let pIndex = state.photos.findIndex(p => {
      return -1 !== p.comments.data.findIndex((c, i) => {
            cIndex = i;
            return c.id === commentId;
          })
    });
    state.photos[pIndex].comments.data[cIndex].posting_reply = true;
  },
  [REPLY_TO_COMMENT_SUCCESS](state, { comment }) {
    const pIndex = state.photos.findIndex(p => p.id === comment.photo_id);
    let cIndex = state.photos[pIndex].comments.data.findIndex(c => c.id === comment.parent_id);

    state.photos[pIndex].comments.data[cIndex].children.data.push(comment);
    state.photos[pIndex].comments.data[cIndex].posting_reply = false;
  },
  [GET_NEW_PHOTO_COMMENTS_SUCCESS](state, { photo }) {
    let pIndex = state.photos.findIndex(p => p.id === photo.id);
    if (pIndex === -1) {
      state.photos.unshift(photo);
      return;
    }
    const firstExistingComment = photo.comments.data.findIndex(c => {
      return c.id === state.photos[pIndex].comments.data[0].id;
    });

    state.photos[pIndex].comments.data.unshift(...photo.comments.data.slice(0, firstExistingComment));
  }
};

export const actions = {
  /**
   * Requests the photos from
   * the /photos API endpoint
   */
  getCommentsByMedias({ commit }) {
    commit(REQUEST_COMMENTS_BY_MEDIAS);
    return new Promise((resolve, reject) => {
      Axios.get('/internal/photos', {
        params: {
          unread_comments: 1,
          includes: ['comments'],
        },
      }).then(({ data }) => {
        commit(GET_COMMENTS_BY_MEDIAS_SUCCESS, { photos: data });
        resolve(data);
      }).catch(response => {
        commit(GET_COMMENTS_BY_MEDIAS_FAILURE, response.data);
        reject(response.data);
      });
    });
  },
  /**
   * Requests the most recent photos from
   * the /photos API endpoint.
   */
  getNewCommentsByMedias({ state, commit }) {
    return new Promise((resolve, reject) => {
      const params = {
        unread_comments: 1,
        includes: ['comments'],
      };
      if (state.photos.length > 0) {
        params.to = moment(state.photos[0].created_at).add(1, 'm').format('YYYY-MM-DD HH:mm:ss');
      }
      Axios.get('/internal/photos', {
        params
      }).then(response => {
        commit(GET_NEW_COMMENTS_BY_MEDIAS_SUCCESS, { photos: response.data });
        resolve(response.data);
      }).catch(response => {
        commit(GET_COMMENTS_BY_MEDIAS_FAILURE, response.data);
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
  getMoreCommentsByMedias({ state, commit }) {
    commit(REQUEST_COMMENTS_BY_MEDIAS);
    return new Promise((resolve, reject) => {
      Axios.get('/internal/photos', {
        params: {
          unread_comments: 1,
          includes: ['comments'],
          cursor: state.cursor.next,
          previous: state.cursor.current,
        },
      }).then(response => {
        commit(GET_MORE_COMMENTS_BY_MEDIAS_SUCCESS, { photos: response.data });
        resolve(response.data);
      }).catch(response => {
        commit(GET_COMMENTS_BY_MEDIAS_FAILURE, response.data);
        reject(response.data);
      });
    });
  },
  /**
   * Makes a PUT request to the API to update an entry.
   * If successful the state will also be updated.
   * The error will be returned in a promise.
   */
  markCommentRead({ commit }, id) {
    return new Promise((resolve, reject) => {
      Axios.put(`/internal/comments/${id}`, {
        read: true,
      }).then(response => {
        commit(
            MARK_COMMENT_READ_SUCCESS,
            {
              commentId: id,
              photoId: response.data.data.photo.data.id
            }
        );
        resolve(response.data);
      }, response => {
        reject(response.data);
      });
    });
  },
  /**
   * Makes a PUT request to the API to update an entry.
   * If successful the state will also be updated.
   * The error will be returned in a promise.
   */
  markAllCommentsRead({ commit }, id) {
    return new Promise((resolve, reject) => {
      Axios.put(`/internal/photos/${id}`, {
        read_comments: true,
      }).then(response => {
        commit(
            MARK_ALL_COMMENT_READ_SUCCESS,
            {
              photoId: id,
            }
        );
        resolve(response.data);
      }, response => {
        reject(response.data);
      });
    });
  },
  replyToComment({ commit }, { parent_id, body }) {
    return new Promise((resolve, reject) => {
      commit(POSTING_REPLY, parent_id);
      Axios.post(`/internal/comments/`, {
        parent_id,
        body,
      }).then(response => {
        commit(
            REPLY_TO_COMMENT_SUCCESS,
            {
              comment: response.data.data,
            }
        );
        resolve(response.data);
      }, response => {
        reject(response.data);
      });
    });
  },
  /**
   * Requests the most recent comments from
   * a photo.
   */
  getNewCommentsFromPhoto({ commit }, photoId) {
    return new Promise((resolve, reject) => {
      const params = {
        includes: ['comments'],
      };
      Axios.get(`/internal/photos/${photoId}`, {
        params
      }).then(response => {
        commit(GET_NEW_PHOTO_COMMENTS_SUCCESS, { photo: response.data.data });
        resolve(response.data);
      }).catch(response => {
        reject(response.data);
      });
    });
  },
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
  isFetchingComments: state => state.isFetching,

  /**
   * Was the state already fetched from the API
   *
   * @param state
   * @return bool
   */
  commentsByMediaLoaded: state => state.loaded,

  /**
   * Returns the list of photos.
   * Can filter photos by
   * consultant.
   *
   * @param state
   * @return object
   */
  photosWithCommentsList: state => state.photos,

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
  hasMorePhotosWithComments: state => state.hasMore,

  /**
   * Returns the total number of unread comments
   * currently in the state.
   *
   * @param state
   * @return int
   */
  totalUnreadComments: state => {
    if (state.photos.length === 0) {
      return 0;
    }
    return state.photos.reduce((acc, photo) => {
      return acc + photo.comments.data.filter(comment => {
            return !comment.read;
          }).length;
    }, 0);
  },
};

export default {
  state,
  mutations,
  actions,
  getters,
};

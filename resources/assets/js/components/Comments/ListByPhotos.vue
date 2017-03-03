<template>
  <div class="columns" v-if="!loading && photos.length > 0">
    <div class="column is-one-quarter">
      <nav class="panel">
        <p class="panel-heading">
          Unread Comments <span class="tag is-primary">{{ totalUnreadComments }}</span>
        </p>
        <a class="panel-block is-active" v-for="(photo, i) in photos" @click="thread = i">
        <span class="panel-icon">
          <figure class="image is-24x24">
            <img :src="photo.thumbnail">
          </figure>
        </span>
          {{ photo.title }} <span class="tag is-primary">{{ unreadComments(photo.comments.data).length }}</span>
        </a>
      </nav>
    </div>
    <div class="column is-two-quarters">
      <div class="comments-list">
        <Single v-for="comment in commentThread" :comment="comment" @requestHistory="displayHistory"></Single>
      </div>
    </div>
    <div class="column is-one-quarter">
      <PhotoCard :photo="selectedPhoto" v-if="!history"></PhotoCard>
      <History v-if="history"></History>
    </div>
  </div>
</template>

<script>
  import { mapActions, mapGetters } from 'vuex';
  import Loader from '../UI/InlineLoader';
  import Single from './Single';
  import History from './ListByUser';
  import PhotoCard from '../Photos/Card';

  export default {
      name: 'Comments-List-By-Photos',
      data:  function () {
          return {
            thread: 0,
            history: null,
          }
        },
      methods: {
        ...mapActions([
          'getCommentsByMedias',
          'getCommentsByUser',
        ]),
        unreadComments(comments) {
          return comments.filter(comment => {
            return !comment.read;
          })
        },
        displayHistory(followerId) {
          if (this.history && this.history === followerId) {
            return this.history = null;
          }
          this.getCommentsByUser(followerId).then(() => {
          this.history = followerId;
          });
        },
      },
      computed: {
       ...mapGetters({
          photos: 'photosWithCommentsList',
          loading: 'isFetchingComments',
          totalUnreadComments: 'totalUnreadComments',
        }),
        commentThread() {
          if (this.photos.length === 0) {
            return [];
          }

          return this.photos[this.thread].comments.data.filter(comment => {
            if (comment.parent_id !== null) {
              return false
            }
            if (comment.children.data.length > 0)
            {
              return comment.children.data.some(child => !child.read)
            }
            return !comment.read;
          });
        },
        selectedPhoto() {
          return this.photos[this.thread];
        },
      },
      components: {
        Loader, Single, PhotoCard, History
      },
      mounted() {
        this.getCommentsByMedias();
      }
  }



</script>

<style lang="scss" scoped>
</style>

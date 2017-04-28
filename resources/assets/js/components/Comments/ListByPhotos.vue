<template>
    <div class="columns comments-manager" v-if="!loading && photos.length > 0">
        <div class="column is-one-quarter">
            <nav class="panel">
                <p class="panel-heading">
                    Unread Comments <span class="tag is-primary">{{ totalUnreadComments }}</span>
                </p>
                <a :class="[i === thread ? 'is-active' : '' ,'panel-block']" v-for="(photo, i) in photos"
                   @click="thread = i">
           <span class="panel-icon">
             <abbr title="Mark all as read">
               <i class="fa fa-eye read-all-button" @click="markAllCommentsRead(photo.id)"></i>
             </abbr>
          </span>
                    <figure class="image is-24x24">
                        <img :src="photo.thumbnail">
                    </figure>
                    {{ photo.title }} <span class="tag is-primary">{{ unreadComments(photo.comments.data).length
                    }}</span>
                </a>
            </nav>
        </div>
        <div class="column is-two-quarters">
            <div class="comments-list">
                <transition-group name="comments-list" tag="div">
                    <Single v-for="comment in commentThread"
                            :key="comment.id"
                            :comment="comment"
                            @requestHistory="displayHistory"
                            @markRead="markCommentRead"
                            @reply="replyToComment"
                    >
                    </Single>
                </transition-group>
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
    data: function() {
      return {
        thread: 0,
        history: null,
      }
    },
    methods: {
      ...mapActions([
        'getCommentsByMedias',
        'getNewCommentsByMedias',
        'getCommentsByUser',
        'markCommentRead',
        'markAllCommentsRead',
        'replyToComment',
        'getNewCommentsFromPhoto',
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
        loaded: 'commentsByMediaLoaded',
        totalUnreadComments: 'totalUnreadComments',
      }),
      commentThread() {
        if (this.photos.length === 0) {
          return [];
        }

        if (this.photos.length < this.thread + 1) {
          this.thread = this.photos.length - 1;
        }

        return this.photos[this.thread].comments.data.filter(comment => {
          if (comment.parent_id !== null) {
            return false
          }
          if (comment.children.data.length > 0) {
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
      if (!this.loading && !this.loaded) {
        this.getCommentsByMedias();
      }
      Echo.private(`user.${Laravel.user.id}`)
          .listen('UserHasNewPhotos', e => {
            this.getNewCommentsByMedias();
          })
          .listen('PhotoHasNewComments', e => {
            this.getNewCommentsFromPhoto(e.photo.id);
          });
    }
  }


</script>

<style lang="scss" scoped>
    .comments-list-enter-active, .comments-list-leave-active {
        transition: all .8s;
    }
    
    .comments-list-enter, .comments-list-leave-to {
        opacity: 0;
    }
</style>

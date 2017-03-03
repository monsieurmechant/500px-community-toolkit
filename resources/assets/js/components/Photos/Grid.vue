<template>
  <div class="columns" v-if="!loading && photos.length > 0">
    <div class="column is-one-quarter">
      <nav class="panel">
        <p class="panel-heading">
          Unread Comments
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
        <article class="media" v-for="comment in commentThread">
          <figure class="media-left">
            <p class="image is-64x64">
              <img :src="comment.follower.data.avatar">
            </p>
          </figure>
          <div class="media-content">
            <div class="content">
              <p>
                <strong>{{ comment.follower.data.name }}</strong>
                <small>
                  <a :href="`http://500px.com/${comment.follower.data.username}`">
                    <span class="icon is-small"><i class="fa fa-500px"></i></span> {{ comment.follower.data.username }}
                  </a>
                </small>
                <small>31m</small>
                <br>
                {{ comment.body }}
              </p>
            </div>
            <article class="media" v-for="child in comment.children.data">
              <figure class="media-left">
                <p class="image is-64x64">
                  <img :src="child.follower.data.avatar">
                </p>
              </figure>
              <div class="media-content">
                <div class="content">
                  <p>
                    <strong>{{ child.follower.data.name }}</strong>
                    <small>
                      <a :href="`http://500px.com/${child.follower.data.username}`">
                        <span class="icon is-small"><i class="fa fa-500px"></i></span> {{ child.follower.data.username }}
                      </a>
                    </small>
                    <small>31m</small>
                    <br>
                    {{ child.body }}
                  </p>
                </div>
              </div>
            </article>
            <nav class="level">
              <div class="level-left">
                <a class="level-item">
                  <span class="icon is-small"><i class="fa fa-reply"></i></span>
                </a>
              </div>
            </nav>
          </div>
          <div class="media-right">
            <a class="button is-primary">
              <span class="icon is-small">
                <i class="fa fa-eye"></i>
              </span>
            </a>
          </div>
        </article>
      </div>
    </div>
    <div class="column is-one-quarter">
      <div class="card">
        <div class="card-image">
          <figure class="image is-1by1">
            <img :src="selectedPhoto.thumbnail" alt="Image">
          </figure>
        </div>
        <header class="card-header">
          <p class="card-header-title">
            {{ selectedPhoto.title }}
          </p>
        </header>
        <div class="card-content">
          <div class="content">
            <p v-html="selectedPhoto.description"></p>
            <br>
            <small>11:09 PM - 1 Jan 2016</small>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import { mapActions, mapGetters } from 'vuex';
  import Loader from '../UI/InlineLoader';

  export default {
      name: 'Photos-Grid',
       data:  function () {
          return {
            thread: 0,
          }
        },
      methods: {
        ...mapActions([
          'getPhotos',
          'getMorePhotos',
          'addInclude',
          'removeInclude',
          'setToPhotosWithUnreadComments',
        ]),
        unreadComments(comments) {
          return comments.filter(comment => {
            return !comment.read;
          })
        },
      },
      computed: {
       ...mapGetters({
          photos: 'photosList',
          loading: 'isFetchingPhotos',
          hasMore: 'hasMorePhotos',
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
        Loader,
      },
      mounted() {
        this.setToPhotosWithUnreadComments();
        this.addInclude('comments');
        this.getPhotos();
      }
  }


</script>

<style lang="scss" scoped>
</style>

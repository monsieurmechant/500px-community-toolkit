<template>
  <div class="card follower-card">
    <template v-if="!loading">
      <div class="card-image" v-if="follower.cover_url">
        <figure class="image is-2by1">
          <img :src="follower.cover_url" alt="Image">
        </figure>
      </div>
      <div class="card-content">
        <div class="media">
          <div class="media-left">
            <figure class="image" style="height: 40px; width: 40px;">
              <img :src="follower.avatars.small.https" alt="Image">
            </figure>
          </div>
          <div class="media-content">
            <p class="title is-4" v-if="follower.fullname">
              {{ follower.fullname }}
            </p>
            <p class="title is-4" v-else>
              {{ follower.username }}
            </p>
            <p class="subtitle is-6">
              <a :href="`http://500px.com/${follower.username}`">
                <span class="icon is-small"><i class="fa fa-500px"></i></span> {{ follower.username }}
              </a>

            </p>
          </div>
        </div>
        <nav class="level">
          <div class="level-item has-text-centered">
            <div>
              <p class="heading icon is-small"><i class="fa fa-users"></i></p>
              <p class="title">{{ follower.followers_count }}</p>
            </div>
          </div>
          <div class="level-item has-text-centered">
            <div>
              <p class="heading icon is-small"><i class="fa fa-heart"></i></p>
              <p class="title">{{ follower.affection }}</p>
            </div>
          </div>
          <div class="level-item has-text-centered">
            <div>
              <p class="heading icon is-small"><i class="fa fa-camera"></i></p>
              <p class="title">{{ follower.photos_count }}</p>
            </div>
          </div>
          <div class="level-item has-text-centered">
            <div>
              <p class="heading icon is-small"><i class="fa fa-picture-o"></i></p>
              <p class="title">{{ follower.galleries_count }}</p>
            </div>
          </div>
        </nav>
        <div class="content has-text-centered">
          <p v-if="follower.about">{{ follower.about }}</p>
        </div>
      </div>
    </template>
    <Loader v-else></Loader>
  </div>
</template>

<script>
    import Axios from 'axios';
    import Loader from '../UI/InlineLoader';

    export default {
        name: 'FullFollowerProfileCard',
        data:  function () {
          return {
            follower: {},
            loading: true,
          }
        },
        mounted() {
          this.loading = true;
          Axios.get(`/internal/followers/${this.followerId}?full=1`).then(response => {
              this.follower = response.data.user;
              this.loading = false;
            }).catch();
        },
        props: {
          'follower-id' : {
            type: Number,
            required: true,
          },
        },
        components: {
          Loader,
        },
    }
</script>

<style lang="scss" scoped>
  .follower-card {
    .card-image {
      overflow: hidden;
      .image img {
        height: auto;
      }
    }
  }
</style>

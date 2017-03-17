<template>
  <div class="user-comments">
    <Loader v-if="loading"></Loader>
    <div class="comments-list" v-if="!loading">
      <h3 class="title is-3">Comment History</h3>
      <p class="subtitle is-5">
        <a :href="comments[0].follower.data.url" target="_blank">
          {{ comments[0].follower.data.name }}
        </a> Comment History on your photos.
      </p>

      <Single v-for="comment in comments" :comment="comment" :photo-thumbnail="true" :interactions="false"></Single>
    </div>
  </div>

</template>

<script>
  import { mapGetters } from 'vuex';
  import Loader from '../UI/InlineLoader';
  import Single from './Single';

  export default {
      name: 'Comments-List-By-Photos',
      computed: {
       ...mapGetters({
          comments: 'userCommentsList',
          loading: 'isFetchingUserComments',
        }),
      },
      components: {
        Loader, Single,
      }
  }




</script>

<style lang="scss" scoped>
</style>

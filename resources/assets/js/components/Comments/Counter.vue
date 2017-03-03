<template>
  <a :class="[loading ? 'is-loading':'', 'is-primary','button']">
    <template v-if="!loading && loaded">
      {{ totalUnreadComments }}{{ hasMore ? '+':'' }}
    </template>
  </a>
</template>
<style>

</style>
<script>
  import { mapActions, mapGetters } from 'vuex';
  export default{
    name: 'Comments-Counter',
    computed: {
     ...mapGetters({
        loading: 'isFetchingComments',
        loaded: 'isLoaded',
        totalUnreadComments: 'totalUnreadComments',
        hasMore: 'hasMore',
      }),
    },
    methods: {
      ...mapActions([
        'getCommentsByMedias',
      ]),
    },
    mounted() {
      if (!this.loading && !this.loaded){
        this.getCommentsByMedias();
      }
    },
  };


</script>

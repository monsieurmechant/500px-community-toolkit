<template>
  <div v-if="!loading">
    <div class="columns photos-grid" v-for="row in chunkedPhotos">
      <div class="column" v-for="photo in row">
        <img :src="photo.thumbnail">
      </div>
    </div>
  </div>
</template>

<script>
  import { mapActions, mapGetters } from 'vuex';
  import Loader from '../UI/InlineLoader';

  export default {
      name: 'Photos-Grid',
      methods: {
        ...mapActions([
          'getPhotos',
          'getMorePhotos',
          'addInclude',
          'removeInclude',
          'setToPhotosWithUnreadComments',
        ]),
      },
      computed: {
       ...mapGetters({
          photos: 'photosList',
          loading: 'isFetchingPhotos',
          hasMore: 'hasMorePhotos',
        }),
        chunkedPhotos() {
          const chunks = [];
          const length = this.photos.length;
          for (let i = 0; i < length;) {
            chunks.push(this.photos.slice(i, i += 5));
          }
          return chunks;
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

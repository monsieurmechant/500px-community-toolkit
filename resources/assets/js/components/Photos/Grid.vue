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
    import Axios from 'axios';
    import Loader from '../UI/InlineLoader';

    export default {
        name: 'Photos-Grid',
        data:  function () {
          return {
            photos: [],
            loading: true,
          }
        },
        methods: {
          fetchPhotos() {
            this.loading = true;
            Axios.get('/internal/photos', {
              params: {
                'unread_comments': 1,
              },
            }).then(response => {
              this.photos = response.data.data;
              this.loading = false;
            }).catch();
          },
        },
        computed: {
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
          this.fetchPhotos();
        }
    }
</script>

<style lang="scss" scoped>
</style>

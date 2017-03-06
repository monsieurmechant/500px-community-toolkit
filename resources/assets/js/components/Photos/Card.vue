<template>
  <div class="card">
    <div class="card-image">
      <figure class="image is-1by1">
        <img :src="photo.thumbnail" alt="Image">
      </figure>
    </div>
    <header class="card-header">
      <p class="card-header-title">
        <a :href="photo.url" target="_blank">{{ photo.title }}</a>
      </p>
    </header>
    <div class="card-content">
      <div class="content">
        <p v-html="photo.description"></p>
        <br>
      </div>
    </div>
  </div>
</template>
<style>

</style>
<script>
  export default{
    name: 'Photo-Card',
    data() {
      return {
        msg: 'hello vue',
      };
    },
    props: {
      photo: {
        type: Object,
        required: true,
      }
    }
  };

</script>

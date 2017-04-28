<template>
    <div id="nav-menu" class="nav-right nav-menu">
        <router-link :to="{name: 'comments'}" active-class="active" class="nav-item">
            My Comments&nbsp;
            <CommentCounter></CommentCounter>
        </router-link>
        <router-link :to="{name: 'followers'}" active-class="active" class="nav-item">
            My Community
        </router-link>
        <span class="nav-item">
            <a id="500px" class="button" target="_blank"
               href="http://500px.com/monsieurmechant">
              <span class="icon">
                <i class="fa fa-500px"></i>
              </span>
              <span>Follow Me</span>
            </a>
        </span>
    </div>
</template>
<style>

</style>
<script>
  import CommentCounter from '../Comments/Counter';
  export default{
    components: {
      CommentCounter,
    },
  };
</script>

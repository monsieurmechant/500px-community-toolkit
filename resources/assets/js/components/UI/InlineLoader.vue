<template>
  <div class="inline-loader slider">
    <div class="line"></div>
    <div class="subline inc"></div>
    <div class="subline dec"></div>
  </div>
</template>
<style lang="scss" scoped>
  .inline-loader.slider{
    position:absolute;
    width: 100%;
    height:5px;
    overflow-x: hidden;
    z-index: 666;
    .line{
      position:absolute;
      opacity: 0.2;
      background:rgba(0, 209, 178, 0.3);
      width:150%;
      height:5px;
    }

    .subline{
      position:absolute;
      background:rgb(0, 209, 178);
      height:5px;
    }
    .inc{
      animation: increase 2s infinite;
    }
    .dec{
      animation: decrease 2s 0.5s infinite;
    }
  }

  @keyframes increase {
    from { left: -5%; width: 5%; }
    to { left: 130%; width: 100%;}
  }
  @keyframes decrease {
    from { left: -80%; width: 80%; }
    to { left: 110%; width: 10%;}
  }
</style>
<script>

  export default{
    name: 'InlineLoader',
  };
</script>

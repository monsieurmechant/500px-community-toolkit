<template>
    <div id="app">
        <div class="container">
            <nav class="nav">
                <div class="nav-left">
                    <router-link :to="{name: 'home'}" active-class="active" class="nav-item is-brand">
                        <span class="icon">
                            <i class="fa fa-wrench"></i>
                        </span>
                        500px Community Toolkit
                    </router-link>
                </div>
                <MainMenu></MainMenu>
            </nav>
        </div>
        <div>
            <section class="container">
                <router-view></router-view>
            </section>
        </div>
    </div>

</template>

<script>
  import MainMenu from './Menu/Main';

  export default {
    name: 'Toolkit',
    components: {
      MainMenu,
    },
  };
</script>

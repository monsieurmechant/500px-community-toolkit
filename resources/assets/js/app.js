/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import store from './store';
import VueRouter from 'vue-router';
import routes from './routes';


Vue.use(VueRouter);

const router = new VueRouter({
  routes,
});

router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.requiresAuth)) {
    // this route requires auth, check if logged in
    // if not, refresh the page and let Laravel
    // handle the redirect to the login page.
    if (Laravel.user) {
      next();
    } else {
      location.reload();
    }
  } else {
    next();
  }
});

const app = new Vue({
  el: '#app',
  router,
  store,
  render: h => h(require('./components/App')),
});

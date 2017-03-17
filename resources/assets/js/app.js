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

Vue.component('top-followers', require('./components/Followers/Top.vue'));
Vue.component('comments-by-photos', require('./components/Comments/ListByPhotos.vue'));
Vue.component('comments-counter', require('./components/Comments/Counter.vue'));

const app = new Vue({
    el: '#app',
    store
});

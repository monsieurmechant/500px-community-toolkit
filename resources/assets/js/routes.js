/* eslint-disable global-require */
export default [
  {
    path: '/comments',
    name: 'comments',
    component: require('./components/Comments/ListByPhotos.vue'),
    meta: { requiresAuth: true },
  },
  {
    path: '/followers',
    name: 'followers',
    component: require('./components/Followers/Top.vue'),
    meta: { requiresAuth: true },
  },
  {
    path: '/',
    name: 'home',
    redirect: '/comments',
    meta: { requiresAuth: true },
  },
  {
    path: '*',
    redirect: '/',
  },
];
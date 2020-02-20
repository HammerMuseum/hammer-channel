// TODO: Is there a way we can use the runtime only build?
import Vue from 'vue/dist/vue.esm';
import VueFilterDateFormat from 'vue-filter-date-format';
import VueRouter from 'vue-router';

import Home from './components/HomeComponent.vue';
import Search from './components/SearchComponent.vue';
import NotFoundComponent from './components/NotFoundComponent.vue';
import VideoComponent from './components/video/VideoComponent.vue';
import FooterComponent from './components/FooterComponent.vue';

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap');
require('videojs-overlay');
require('video.js');

window.VIDEOJS_NO_BASE_THEME = true;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */
const files = require.context('./', true, /\.vue$/i);
files.keys().map((key) => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

const routes = [
  {
    path: '/', name: 'app', component: Home,
  },
  {
    path: '/search/:params?', name: 'search', component: Search,
  },
  {
    path: '/video/:id', name: 'video', component: VideoComponent, props: true,
  },
  {
    path: '*', component: NotFoundComponent,
  },
];

const router = new VueRouter({
  mode: 'history',
  routes,
});

Vue.use(VueFilterDateFormat);
Vue.use(VueRouter);

const app = new Vue({ // eslint-disable-line
  el: '#main-content',
  router,
});

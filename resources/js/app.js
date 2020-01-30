// TODO: Is there a way we can use the runtime only build?
import Vue from 'vue/dist/vue.esm';
import VueFilterDateFormat from 'vue-filter-date-format';
import VueRouter from 'vue-router';

import VideoComponent from './components/VideoComponent.vue';
import VideoPlayer from './components/VideoPlayer.vue';
import Home from './components/HomeComponent.vue';
import ResultGrid from './components/ResultGridComponent.vue';
import Search from './components/SearchComponent.vue';
import App from './components/App.vue';


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

Vue.use(VueFilterDateFormat);
Vue.use(VueRouter);

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
Vue.component('video-component', VideoComponent);
Vue.component('video-player', VideoPlayer);
Vue.component('home-component', Home);
Vue.component('result-grid', ResultGrid);
Vue.component('search-component', Search);

const router = new VueRouter({
    mode: 'history',
    routes: [
        { path: '/', name: 'app', component: Home },
        { path: '/search', name: 'search', component: Search },
        { path: '/video/:id', name: 'video', component: VideoComponent, props: true }
    ]
});
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#main-content',
    router: router
});

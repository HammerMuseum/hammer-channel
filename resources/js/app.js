/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import vjs from 'video.js';
require('./bootstrap');
// VIDEOJS_NO_BASE_THEME = true;
require('video.js');
require('videojs-overlay');

(function(window, videojs) {
    var player = vjs('hammer-video-player');
    var overlay_content = '<div class="myOverlay"><h2>Title of Video</h2></div>';
    player.overlay({
        overlays: [{
            start: 'playing',
            end: 'pause'
        }, {
            start: 'pause',
            content: overlay_content,
            end: 'playing',
            align: 'top'
        }]
    });
}(window, window.videojs));

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

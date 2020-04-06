// TODO: Is there a way we can use the runtime only build?
// import './bootstrap';
import 'svgxuse';
import Vue from 'vue';
import VueCheckView from 'vue-check-view';
import VueFilterDateFormat from 'vue-filter-date-format';
import VueScrollTo from 'vue-scrollto';
import VueSocialSharing from 'vue-social-sharing';
import router from './router';

// /**
//  * The following block of code may be used to automatically register your
//  * Vue components. It will recursively scan this directory for the Vue
//  * components and automatically register them with their "basename".
//  *
//  * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
//  */
const files = require.context('./', true, /\.vue$/i);
files.keys().map((key) => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.use(VueFilterDateFormat);
Vue.use(VueSocialSharing);
Vue.use(VueCheckView);
Vue.use(VueScrollTo);

const app = new Vue({ // eslint-disable-line
  el: '#app',
  router,
});

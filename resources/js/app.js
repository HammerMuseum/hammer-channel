// TODO: Is there a way we can use the runtime only build of vue?
import 'svgxuse';
import Vue from 'vue';
import VueCheckView from 'vue-check-view';
import VueFilterDateFormat from 'vue-filter-date-format';
import VueProgressBar from 'vue-progressbar';
import VueScrollTo from 'vue-scrollto';
import VueSocialSharing from 'vue-social-sharing';
import vClickOutside from 'v-click-outside';
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

Vue.use(vClickOutside);
Vue.use(VueFilterDateFormat);
Vue.use(VueSocialSharing);
Vue.use(VueCheckView);
Vue.use(VueScrollTo);
Vue.use(VueProgressBar, {
  color: '#ee2a7b',
  failedColor: 'red',
  height: '2px',
});

const app = new Vue({ // eslint-disable-line
  el: '#app',
  router,
  created() {
    document.addEventListener('keydown', this.onKeyDown, true);
    document.addEventListener('mousedown', this.onPointerDown, true);
  },
  destroyed() {
    document.removeEventListener('keydown', this.onKeyDown, true);
    document.removeEventListener('mousedown', this.onPointerDown, true);
  },
  methods: {
    onKeyDown(e) {
      if (e.metaKey || e.altKey || e.ctrlKey) return;
      document.body.dataset.interactionMode = 'keyboard';
    },
    onPointerDown() {
      document.body.dataset.interactionMode = 'pointer';
    },
  },
});

// TODO: Is there a way we can use the runtime only build of vue?
import 'svgxuse';
import Vue from 'vue';
import VueCheckView from 'vue-check-view';
import VueFilterDateFormat from 'vue-filter-date-format';
import VueProgressBar from 'vue-progressbar';
import VueScrollTo from 'vue-scrollto';
import router from './router';
import { store } from './store';

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
  computed: {
    overlayOpen() {
      return store.searchOverlayActive || store.facetOverlayActive || store.footerActive;
    },
  },
  watch: {
    overlayOpen() {
      if (this.overlayOpen) {
        document.body.style.overflow = 'hidden';
      } else {
        document.body.style.overflow = '';
      }
    },
  },
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

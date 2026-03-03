import 'intersection-observer';
import { createApp, configureCompat } from 'vue';
import VueAnnouncer from '@vue-a11y/announcer';
import { createGtm } from '@gtm-support/vue-gtm';
import VueProgressBar from '@aacassandra/vue3-progressbar';
// import { VSkip } from 'vuetensils/src/components';
import router from './router';
import { store } from './store';
import App from './components/App.vue';

// Enable Vue 2 compatibility mode
configureCompat({
  MODE: 2, // Run in Vue 2 compatibility mode
  GLOBAL_MOUNT: false, // Use createApp instead of new Vue
});

// /**
//  * The following block of code may be used to automatically register your
//  * Vue components. It will recursively scan this directory for the Vue
//  * components and automatically register them with their "basename".
//  *
//  * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
//  */
// const files = require.context('./', true, /\.vue$/i);
// files.keys().map((key) => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

const app = createApp({
  computed: {
    overlayOpen() {
      return (
        store.searchOverlayActive
        || store.facetOverlayActive
        || store.footerActive
      );
    },
  },
  watch: {
    $route(to, from) {
      if (to.path !== from.path) {
        this.$nextTick(() => {
          setTimeout(() => {
            this.setFocus();
          }, 0);
        });
      }
    },
    overlayOpen() {
      document.body.classList.toggle('overlay--open', this.overlayOpen);
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
    this.$Progress.start();
    this.$router.beforeEach((to, from, next) => {
      if (from.hash !== to.hash) return;
      this.$Progress.start();
      next();
    });
    this.$router.afterEach(() => {
      this.$Progress.finish();
    });
  },
  unmounted() {
    // changed from destroyed
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
    setFocus() {
      this.$el.focus();
    },
  },
  render: (h) => h(App),
});

// Custom directive that replaces `vue-check-view` using IntersectionObserver.
app.directive('view', {
  mounted(el, binding) {
    const callback = binding.value;
    if (typeof callback !== 'function') return;

    const observer = new IntersectionObserver(
      // Iterate over IntersectionObserverEntries
      (entries) => {
        entries.forEach((entry) => {
          const rect = entry.boundingClientRect;
          /* Normalised intersection ratio (0–1), used by handlers to decide when
          a section is "active". */
          const percentInView = entry.intersectionRatio;

          // Run callback when el in view
          callback({
            percentInView,
            target: {
              element: el,
              rect,
            },
          });
        });
      },
      {
        threshold: [0, 0.25, 0.5, 0.75, 1],
      },
    );

    /* Keep a reference on the element so we can disconnect the observer when
    the element is unmounted. */
    el.__viewObserver__ = observer;
    observer.observe(el);
  },
  unmounted(el) {
    if (el.__viewObserver__) {
      el.__viewObserver__.disconnect();
      delete el.__viewObserver__;
    }
  },
});

// Register plugins
app.use(router);
app.use(createGtm({
  id: process.env.MIX_GTM_ID ? process.env.MIX_GTM_ID : 'GTM-XXXXXXX',
  defer: false,
  enabled: process.env.MIX_PROD,
  debug: false,
  loadScript: true,
}));
app.use(VueAnnouncer, {}, router);
app.use(VueProgressBar, {
  color: '#ee2a7b',
  failedColor: 'red',
  height: '2px',
});

// Auto-register components
const files = require.context('./', true, /\.vue$/i);
files.keys().forEach((key) => {
  const component = files(key).default;
  const name = key.split('/').pop().split('.')[0];
  app.component(name, component);
});

// app.component('VSkip', VSkip);

app.mount('#app');

import Vue from 'vue';
import VueFilterDateFormat from 'vue-filter-date-format';
import VueGtm from 'vue-gtm';
import VueRouter from 'vue-router';
import VideoEmbed from './components/video/VideoEmbed.vue';

Vue.use(VueFilterDateFormat);
Vue.use(VueGtm, {
  id: process.env.MIX_GTM_ID ? process.env.MIX_GTM_ID : 'GTM-XXXXXXX',
  defer: false,
  enabled: process.env.MIX_PROD,
  debug: false,
  loadScript: true,
});

Vue.component('VideoEmbed', VideoEmbed);

Vue.use(VueRouter);
const router = new VueRouter({
  mode: 'history',
  routes: [
    { path: '/container', component: VideoEmbed },
  ],
});

const app = new Vue({ // eslint-disable-line
  router,
}).$mount('#app');

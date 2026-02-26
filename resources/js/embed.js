import { createApp } from 'vue';
import { createGtm } from '@gtm-support/vue-gtm';
import VueRouter from 'vue-router';
import VideoEmbed from './components/video/VideoEmbed.vue';

Vue.use(createGtm({
  id: process.env.MIX_GTM_ID ? process.env.MIX_GTM_ID : 'GTM-XXXXXXX',
  defer: false,
  enabled: process.env.MIX_PROD,
  debug: false,
  loadScript: true,
}));

Vue.component('VideoEmbed', VideoEmbed);

Vue.use(VueRouter);
const router = new VueRouter({
  mode: 'history',
  routes: [
    { path: '/container', component: VideoEmbed },
  ],
});

const app = createApp();
app.use(router);
app.mount('#app');

import { createApp } from 'vue';
import { createGtm } from '@gtm-support/vue-gtm';
import VideoEmbed from './components/video/VideoEmbed.vue';

const app = createApp({});

app.component('VideoEmbed', VideoEmbed);

app.use(createGtm({
  id: process.env.MIX_GTM_ID ? process.env.MIX_GTM_ID : 'GTM-XXXXXXX',
  defer: false,
  enabled: process.env.MIX_PROD,
  debug: false,
  loadScript: true,
}));

app.mount('#app');

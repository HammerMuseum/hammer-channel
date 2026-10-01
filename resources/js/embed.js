import { createApp } from 'vue';
import VideoEmbed from './components/video/VideoEmbed.vue';

const app = createApp({});

app.component('VideoEmbed', VideoEmbed);

app.mount('#app');

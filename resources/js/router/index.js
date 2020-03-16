import Vue from 'vue';
import VueRouter from 'vue-router';
import Home from '../components/HomeComponent.vue';
import Search from '../components/SearchComponent.vue';
import NotFoundComponent from '../components/NotFoundComponent.vue';
import VideoComponent from '../components/video/VideoComponent.vue';

Vue.use(VueRouter);

const routes = [
  {
    path: '/', name: 'app', component: Home,
  },
  {
    path: '/search/:params?', name: 'search', component: Search, props: (route) => ({ facetQuery: route.query.facets }),
  },
  {
    path: '/video/:id', name: 'video', component: VideoComponent, props: true,
  },
  {
    path: '*', component: NotFoundComponent,
  },
];

export default new VueRouter({
  mode: 'history',
  routes,
});

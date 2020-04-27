import Vue from 'vue';
import VueRouter from 'vue-router';
import Home from '../components/HomeComponent.vue';
import Search from '../components/SearchPage.vue';
import NotFound from '../components/NotFoundComponent.vue';
import Video from '../components/video/Video.vue';

Vue.use(VueRouter);

const routes = [
  {
    path: '/', name: 'app', component: Home,
  },
  {
    path: '/search/:params?', name: 'search', component: Search, props: (route) => ({ facetQuery: route.query.facets }),
  },
  {
    path: '/video/:id', name: 'video', component: Video, props: true,
  },
  {
    path: '*', component: NotFound,
  },
];

export default new VueRouter({
  mode: 'history',
  routes,
});

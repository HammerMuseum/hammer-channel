<template>
  <div class="listing">
    <router-link :to="{name: 'search'}">
      Search
    </router-link>
    <h1 class="title">
      {{ title }}
    </h1>
    <div class="topics">
      <ul class="topics__list">
        <li class="topics__list-item" v-for="(videos, topic) in topics" :key="topic">
          <a :href="`#${topic}`">{{topic}}</a>
        </li>
      </ul>
    </div>

    <div class="videos">
      <div v-for="(topic, topic_name) in topics" :key="topic_name" :id="topic_name">
        <h2>{{ topic_name }}</h2>
        <VueSlickCarousel v-bind="settings" :arrows="true">
          <div class="video" v-for="video in topic">
            <div>
              <img :src="video._source['thumbnail_url']">
              <span>{{video._source['title']}}</span>
            </div>
          </div>
          <div class="video">
            <router-link>
              See all
            </router-link>
          </div>
        </VueSlickCarousel>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import ResultGrid from './ResultGridComponent.vue';
import mixin from '../mixin';
import VueSlickCarousel from 'vue-slick-carousel'
// optional style for arrows & dots


export default {
  name: 'Home',
  components: {
    ResultGrid,
    VueSlickCarousel
  },
  mixins: [mixin],
  data() {
    return {
      title: this.title,
      videos: this.videos,
      pager: this.pager,
      topics: this.topics,
      settings: {
        "slidesToShow": 3,
        "infinite": false,
        "touchThreshold": 5
      }
    };
  },
  mounted() {
    this.getPageData('?term=all');
  },
  methods: {
    getPageData(params = '') {
      axios
        .get(`/json${params}`)
        .then((response) => {
          this.title = response.data.title;
          this.pager = response.data.pager;
          this.videos = response.data.videos;
          this.topics = response.data.topics;
        });
    },
  },
};
</script>

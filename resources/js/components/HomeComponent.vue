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
          <a :href="`#${stripChars(topic)}`">{{topic}}</a>
        </li>
      </ul>
    </div>

    <div class="videos">
      <div v-for="(topic, topic_name) in topics" :key="topic_name" :id="stripChars(topic_name)" :class="`topic`">
        <h2 class="topic__name">{{ topic_name }}</h2>
        <VueSlickCarousel v-bind="settings" :arrows="true">
          <!-- Custom arrow -->
          <template #prevArrow="arrowOption">
            <div class="videos__navigation">
              {{ arrowOption.currentSlide }}/{{ arrowOption.slideCount }}
            </div>
          </template>
          <div class="video" v-for="video in topic">
            <router-link
              :to="{name: 'video', params: {id: video._source['title_slug']}}"
            >
              <div class="video__thumbnail">
                <img :src="video._source['thumbnail_url']">
                <div class="video__title">
                  <span>{{video._source['title']}}</span>
                </div>
              </div>
            </router-link>
          </div>
          <div class="video topic__see-all">
            <div class="video__thumbnail">
              <router-link class="topic-link" :to="{name: 'search', params: {params:`?facets=[3]topics:${topic_name}`}}">
                See all videos tagged
                <span class="topic-name">{{ topic_name }}</span>
              </router-link>
            </div>
          </div>
          <!-- Custom arrow -->
          <template #nextArrow="arrowOption">
            <div class="videos__navigation">
              {{ arrowOption.currentSlide }}/{{ arrowOption.slideCount }}
            </div>
          </template>
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
        "slidesToShow": 3.5,
        "infinite": false,
        "touchThreshold": 5,
        "responsive": [
          {
            "breakpoint": 1200,
            "settings": {
              "slidesToShow": 3,
            }
          },
          {
            "breakpoint": 965,
            "settings": {
              "slidesToShow": 2.5
            }
          },
          {
            "breakpoint": 800,
            "settings": {
              "slidesToShow": 2,
            }
          },
          {
            "breakpoint": 650,
            "settings": {
              "slidesToShow": 1.5,
            }
          },
          {
            "breakpoint": 500,
            "settings": {
              "slidesToShow": 1,
            }
          }
        ]
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
    stripChars(topic) {
      let strippedTopic = topic.replace(' & ', '');
      return strippedTopic.replace(' ', '');
    }
  },
};
</script>

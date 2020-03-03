<template>
  <div class="listing">
    <ul class="nav-list">
      <li class="nav-item">
        <router-link :to="{name: 'app'}">
          Home
        </router-link>
      </li>
      <li class="nav-item">
        <router-link :to="{name: 'search'}">
          Search
        </router-link>
      </li>
    </ul>
    <h1 class="title">
      {{ title }}
    </h1>
    <div class="topics">
      <ul class="topics__list">
        <li
          v-for="(videos, topic) in topics"
          :key="topic"
          class="topics__list-item"
        >
          <a :href="`#${stripChars(topic)}`">{{ topic }}</a>
        </li>
      </ul>
    </div>

    <div class="videos">
      <div
        v-if="featured"
        id="featured"
        class="topic"
      >
        <h2 class="topic__name">
          Featured
        </h2>
        <VueSlickCarousel
          v-bind="settings"
          :arrows="true"
        >
          <!-- Custom arrow -->
          <template #prevArrow="arrowOption">
            <div class="videos__navigation">
              {{ arrowOption.currentSlide }}/{{ arrowOption.slideCount }}
            </div>
          </template>
          <div
            v-for="video in featured"
            class="video"
          >
            <router-link
              :to="{name: 'video', params: {id: video['title_slug']}}"
            >
              <div class="video__thumbnail">
                <img :src="video['thumbnail_url']">
                <div class="video__title">
                  <span>{{ video['title'] }}</span>
                </div>
              </div>
            </router-link>
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

    <div
      v-for="(topic, topic_name) in topics"
      :id="stripChars(topic_name)"
      :key="topic_name"
      :class="`topic`"
    >
      <h2 class="topic__name">
        {{ topic_name }}
      </h2>
      <VueSlickCarousel
        v-bind="settings"
        :arrows="true"
      >
        <!-- Custom arrow -->
        <template #prevArrow="arrowOption">
          <div class="videos__navigation">
            {{ arrowOption.currentSlide }}/{{ arrowOption.slideCount }}
          </div>
        </template>
        <div
          v-for="video in topic"
          class="video"
        >
          <router-link
            :to="{name: 'video', params: {id: video._source['title_slug']}}"
          >
            <div class="video__thumbnail">
              <img :src="video._source['thumbnail_url']">
              <div class="video__title">
                <span>{{ video._source['title'] }}</span>
              </div>
            </div>
          </router-link>
        </div>
        <div class="video topic__see-all">
          <div class="video__thumbnail">
            <router-link
              class="topic-link"
              :to="{name: 'search', query: {topics: topic_name}}"
            >
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
import VueSlickCarousel from 'vue-slick-carousel';
import mixin from '../mixins/getRouteData';

export default {
  name: 'Home',
  components: {
    VueSlickCarousel,
  },
  mixins: [mixin],
  data() {
    return {
      title: null,
      videos: null,
      pager: null,
      topics: null,
      featured: false,
      settings: {
        slidesToShow: 3.5,
        infinite: false,
        touchThreshold: 5,
        responsive: [
          {
            breakpoint: 1200,
            settings: {
              slidesToShow: 3,
            },
          },
          {
            breakpoint: 965,
            settings: {
              slidesToShow: 2.5,
            },
          },
          {
            breakpoint: 800,
            settings: {
              slidesToShow: 2,
            },
          },
          {
            breakpoint: 650,
            settings: {
              slidesToShow: 1.5,
            },
          },
          {
            breakpoint: 500,
            settings: {
              slidesToShow: 1,
            },
          },
        ],
      },
    };
  },
  mounted() {
    this.$nextTick(() => {
      this.getFeatured();
    });
  },
  methods: {
    getFeatured() {
      axios
        .get(`${process.env.MIX_DATASTORE_URL}playlists/12`)
        .then((response) => {
          this.featured = response.data.data.videos;
        });
    },
    getPageData() {
      axios
        .get('?term=all')
        .then((response) => {
          this.title = response.data.title;
          this.pager = response.data.pager;
          this.videos = response.data.videos;
          this.topics = response.data.topics;
        });
    },
    stripChars(topic) {
      const strippedTopic = topic.replace(' & ', '');
      return strippedTopic.replace(' ', '');
    },
  },
};
</script>

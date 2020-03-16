<template>
  <div class="listing">
    <h1 class="title">
      {{ title }}
    </h1>

    <NavigationBar :items="topics" />

    <div class="videos">
      <div
        v-if="featured"
        id="featured"
        class="topic"
      >
        <VueSlickCarousel
          v-bind="featuredSettings"
          :arrows="true"
          class="featured-carousel"
        >
          <!-- Custom arrow -->
          <template #prevArrow="arrowOption">
            <div class="videos__navigation">
              {{ arrowOption.currentSlide }}/{{ arrowOption.slideCount }}
            </div>
          </template>
          <div
            v-for="video in featured"
            :key="video.id"
            class="video featured-video"
          >
            <router-link
              :to="{name: 'video', params: {id: video['title_slug']}}"
            >
              <div class="featured-video__thumbnail">
                <span class="video__duration">{{ video['duration'] }}</span>
                <img :src="video['thumbnail_url']">
                <div class="video__info">
                  <span class="video__info-title">{{ getTitle(video) }}</span>
                  <div class="video__info-teaser">
                    <div class="video__info-subtitle">
                      {{ getTitle(video, true) }}
                    </div>
                    {{ video['description'].substr(0, 200) }}
                  </div>
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
      <div
        v-for="(topic, topic_name) in topics"
        :id="topic_name | filterId"
        :key="topic_name"
        :class="`topic`"
      >
        <h2 class="topic__name video-meta__title">
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
            v-for="video in topic['videos']"
            :key="video.id"
            class="video"
          >
            <router-link
              :to="{name: 'video', params: {id: video._source['title_slug']}}"
            >
              <div class="video__thumbnail">
                <span class="video__duration">{{ video._source['duration'] }}</span>
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
                See all {{ topic['count'] }} videos tagged
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
  filters: {
    filterId(value) {
      return value.replace(/[\s&]/gi, '').toLowerCase();
    },
  },
  mixins: [mixin],
  data() {
    return {
      title: null,
      videos: null,
      pager: null,
      topics: null,
      featured: false,
      featuredSettings: {
        edgeFriction: 0.35,
        infinite: false,
        speed: 500,
        slidesToShow: 1,
        slidesToScroll: 1,
      },
      settings: {
        slidesToShow: 2.5,
        infinite: false,
        touchThreshold: 5,
        responsive: [
          {
            breakpoint: 1200,
            settings: {
              slidesToShow: 2.5,
            },
          },
          {
            breakpoint: 965,
            settings: {
              slidesToShow: 2,
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
    getTitle(metadata, subtitle = false) {
      if (subtitle && metadata.quote === '') {
        return '';
      }
      if (metadata.quote !== '' && !subtitle) {
        return `"${metadata.quote}"`;
      }
      return metadata.title;
    },
  },
};
</script>

<template>
  <div class="listing">
    <NavigationBar
      :items="topics"
      :active-item="currentSectionInView"
      :classes="['topic-menu']"
    />
    <content-loader
      v-if="!featured"
      :width="800"
      :height="250"
      :animate="false"
      primary-color="#c6c6c6"
      secondary-color="#c6c6c6"
    >
      <rect
        x="425"
        y="3"
        rx="2"
        ry="2"
        width="361"
        height="26"
      />
      <rect
        x="425"
        y="44"
        rx="2"
        ry="2"
        width="361"
        height="26"
      />
      <rect
        x="6"
        y="2"
        rx="2"
        ry="2"
        width="400"
        height="192"
      />
      <rect
        x="425"
        y="83"
        rx="2"
        ry="2"
        width="361"
        height="26"
      />
      <rect
        x="425"
        y="124"
        rx="2"
        ry="2"
        width="361"
        height="26"
      />
    </content-loader>

    <Carousel
      v-else
      id="featured"
      title="Featured programs"
      :controls="true"
      :classes="['carousel--featured']"
      :options="featuredSettings"
      :show-heading="false"
    >
      <featured-carousel-slide
        v-for="video in featured"
        :key="video.id"
        :item="video"
      />
    </Carousel>

    <div
      v-for="(topic, name) in topics"
      :key="topic.id"
      v-view="viewHandler"
      :data-section-id="topic.id"
    >
      <carousel
        :id="topic.id"
        :controls="true"
        :title="name"
        :options="carouselSettings"
      >
        <carousel-slide
          v-for="video in topic.videos"
          :key="video.id"
          :item="video._source"
        />
        <div class="video topic__see-all">
          <router-link
            class="topic-link"
            :to="{name: 'search', query: {topics: name}}"
          >
            {{ seeAllLinkText(topic) }}
            <span class="topic-name">{{ name }}</span>
          </router-link>
        </div>
      </carousel>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { ContentLoader } from 'vue-content-loader';
import Carousel from './Carousel.vue';
import CarouselSlide from './CarouselSlide.vue';
import FeaturedCarouselSlide from './FeaturedCarouselSlide.vue';
import mixin from '../mixins/getRouteData';

export default {
  name: 'Home',
  components: {
    Carousel,
    CarouselSlide,
    ContentLoader,
    FeaturedCarouselSlide,
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
      currentSectionInView: null,
      featuredSettings: {
        edgeFriction: 0.35,
        infinite: false,
        speed: 500,
        slidesToShow: 1,
        slidesToScroll: 1,
      },
      carouselSettings: {
        slidesToShow: 3.5,
        slidesToScroll: 2,
        responsive: [
          {
            breakpoint: 1200,
            settings: {
              slidesToShow: 2.5,
              slidesToScroll: 1,
            },
          },
          {
            breakpoint: 650,
            settings: {
              slidesToShow: 1.5,
              slidesToScroll: 1,
            },
          },
        ],
      },
    };
  },
  mounted() {
    this.getFeatured();
  },
  methods: {
    getFeatured() {
      axios
        .get(`${process.env.MIX_DATASTORE_URL}playlists/Featured`)
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
    seeAllLinkText(topic) {
      const count = topic.count;
      const videos = count > 1 ? 'videos' : 'video';
      return `See all ${count} ${videos} tagged`;
    },
    viewHandler(e) {
      if (e.percentInView >= 0.5) {
        this.currentSectionInView = e.target.element.dataset.sectionId;
      }
    },
  },
};
</script>

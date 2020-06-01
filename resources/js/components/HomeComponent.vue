<template>
  <div class="container">
    <VSkip to="#featured">
      Skip To Main Content
    </VSkip>

    <div class="page-wrapper page-wrapper--full">
      <NavigationBar
        :items="topics"
        :active-item="currentSectionInView"
        :classes="['topic-menu']"
      />
      <Loader v-if="!featured" />
      <Carousel
        v-else
        id="featured"
        title="Featured programs"
        :controls="true"
        :classes="['carousel--featured']"
        :options="featuredCarouselOptions"
        :show-heading="false"
      >
        <FeaturedCarouselSlide
          v-for="video in featured"
          :key="video.id"
          :item="video"
        />
      </Carousel>

      <div class="carousels">
        <template v-for="(topic, name, idx) in topics">
          <div
            v-if="idx === 3"
            class="inline-block--search"
          >
            <div class="background--grate">
              <SearchBar />
            </div>
          </div>
          <div
            :key="topic.id"
            v-view="viewHandler"
            :data-section-id="topic.id"
          >
            <Carousel
              :id="topic.id"
              :controls="true"
              :title="name"
              :options="{}"
            >
              <template #heading>
                <RouterLink :to="{name: 'search', query: {topics: name}}">
                  {{ name }}
                </RouterLink>
              </template>
              <CarouselSlide
                v-for="video in topic.videos"
                :key="video.id"
                :item="video._source"
              />
              <div class="carousel__slide see-more">
                <router-link
                  class="ui-card"
                  :to="{name: 'search', query: {topics: name}}"
                >
                  <div class="ui-card__thumbnail">
                    <div class="ui-card__thumbnail-image">
                      <span class="see-more__link">
                        {{ seeAllLinkText(topic, name) }}
                      </span>
                    </div>
                  </div>
                </router-link>
              </div>
            </Carousel>
          </div>
        </template>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { VSkip } from 'vuetensils/src/components';
import Carousel from './Carousel.vue';
import CarouselSlide from './CarouselSlide.vue';
import FeaturedCarouselSlide from './FeaturedCarouselSlide.vue';
import Loader from './Loader.vue';
import mixin from '../mixins/getRouteData';

export default {
  name: 'Home',
  components: {
    Carousel,
    CarouselSlide,
    FeaturedCarouselSlide,
    Loader,
    VSkip,
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
      featuredCarouselOptions: { wrapAround: true, pageDots: true },
      currentSectionInView: null,
    };
  },
  mounted() {
    this.getFeatured();
    document.body.classList.add('front');
  },
  destroyed() {
    document.body.classList.remove('front');
  },
  methods: {
    getFeatured() {
      axios
        .get(`${process.env.MIX_DATASTORE_URL}playlists/Featured`)
        .then((response) => {
          this.featured = response.data.data.videos;
        }).catch((err) => {
          console.error(err);
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
        }).catch((err) => {
          console.error(err);
        });
    },
    seeAllLinkText(topic, name) {
      const count = topic.count;
      const videos = count > 1 ? 'videos' : 'video';
      return `See all ${count} ${videos} tagged ${name}`;
    },
    viewHandler(e) {
      if (e.percentInView > 0 && e.percentInView < 0.4) {
        console.log(e.target.element.dataset.sectionId);
        // this.currentSectionInView = e.target.element.dataset.sectionId;
      }
    },
  },
};
</script>

<style>
.inline-block--search {
  background: #fff;
  margin-left: -8px;
}

.inline-block--search .search-bar {
  padding: 48px 0;
}
</style>

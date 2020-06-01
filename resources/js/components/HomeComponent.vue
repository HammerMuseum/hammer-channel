<template>
  <div class="container">
    <VSkip to="#featured">
      Skip To Main Content
    </VSkip>

    <div class="page-wrapper page-wrapper--full">
      <NavigationBar
        :items="videos"
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
        <template v-for="({id, label, count, hits}, idx) in videos">
          <div
            v-if="idx === 3"
            class="inline-block--search"
          >
            <div class="background--grate">
              <SearchBar />
            </div>
          </div>
          <div
            :key="id"
            v-view="viewHandler"
            :data-section-id="id"
          >
            <Carousel
              :id="id"
              :controls="true"
              :title="label"
              :options="{}"
            >
              <template #heading>
                <RouterLink :to="{name: 'search', query: {topics: label}}">
                  {{ label }}
                </RouterLink>
              </template>
              <CarouselSlide
                v-for="video in hits"
                :key="video.id"
                :item="video"
              />
              <div class="carousel__slide see-more">
                <router-link
                  class="ui-card"
                  :to="{name: 'search', query: {topics: label}}"
                >
                  <div class="ui-card__thumbnail">
                    <div class="ui-card__thumbnail-image">
                      <span class="see-more__link">
                        {{ seeAllLinkText(count, label) }}
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
      videos: null,
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
        .get('/api')
        .then((response) => {
          this.content = response.data.videos;
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
      if (e.percentInView === 1 && e.percentTop < 0.6) {
        this.currentSectionInView = e.target.element.dataset.sectionId;
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

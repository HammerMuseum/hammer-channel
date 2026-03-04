<template>
  <div class="container container--full">
    <div
      id="start-of-content"
      class="page-wrapper page-wrapper--full"
    >
      <p class="strapline">
        {{ copy.strapline }}
      </p>
      <Loader
        v-if="!featured"
        class="carousel--full-width"
      />
      <Carousel
        v-else
        id="featured"
        title="Featured programs"
        :controls="true"
        :classes="['carousel--featured']"
        :options="featuredCarouselOptions"
        :show-heading="false"
        :full-width="true"
      >
        <FeaturedCarouselSlide
          v-for="video in featured"
          :key="video.id"
          :item="video"
        />
      </Carousel>

      <div class="carousels">
        <template
          v-for="({id, label, count, hits}, idx) in videos"
          :key="id"
        >
          <div
            v-if="idx === 3"
            :key="`${id}-search`"
            class="inline-block--search"
          >
            <div class="background--grate">
              <SearchBar
                id-prefix="carousel"
              />
            </div>
          </div>
          <div
            v-view="viewHandler"
            :data-section-id="id"
          >
            <Carousel
              :id="id"
              :controls="true"
              :title="label"
              :options="{ groupCells, contain: true }"
            >
              <template #heading>
                <RouterLink
                  :aria-label="`A selection of videos from on topic: ${label}`"
                  :to="{name: 'search', query: {topics: label}}"
                >
                  {{ label }}
                </RouterLink>
              </template>
              <CarouselSlide
                v-for="video in hits"
                :key="video.id"
                :item="video"
              />
              <div class="carousel__slide">
                <router-link
                  class="ui-card see-more"
                  :to="{name: 'search', query: {topics: label}}"
                >
                  <span class="see-more__text">
                    {{ seeAllLinkText(count, label) }}
                    <BaseIcon
                      width="36"
                      height="36"
                      view-box="0 0 36 36"
                    >
                      <NextIcon />
                    </BaseIcon>
                  </span>
                </router-link>
              </div>
            </Carousel>
          </div>
        </template>
      </div>
    </div>
    <NavigationBar
      :items="videos"
      :active-item="currentSectionInView"
      :classes="['topic-menu']"
    />
  </div>
</template>

<script>
import axios from 'axios';
import { useWindowSize } from 'vue-window-size';
import Carousel from './Carousel.vue';
import CarouselSlide from './CarouselSlide.vue';
import FeaturedCarouselSlide from './FeaturedCarouselSlide.vue';
import Loader from './Loader.vue';
import { store } from '../store';

export default {
  name: 'Home',
  components: {
    Carousel,
    CarouselSlide,
    FeaturedCarouselSlide,
    Loader,
  },
  beforeRouteEnter(to, from, next) {
    const getData = function () {
      return new Promise((resolve) => {
        const initialState = JSON.parse(window.INITIAL_STATE) || {};
        if (!initialState.path || to.path !== initialState.path) {
          // Check if the query object is empty
          if (Object.keys(to.query).length === 0 && to.query.constructor === Object) {
            axios.get(`/api${to.path}`).then(({ data }) => {
              resolve(data);
            });
          } else {
            axios.get(`/api${to.path}`, { params: to.query }).then(({ data }) => {
              resolve(data);
            });
          }
        } else {
          resolve(initialState);
        }
      });
    };

    getData(to).then((data) => {
      next(
        (vm) => Object.assign(vm.$data, data),
      );
    });
  },
  setup() {
    const { width: windowWidth } = useWindowSize();

    return { windowWidth };
  },
  data() {
    return {
      currentSectionInView: null,
      featured: false,
      featuredCarouselOptions: { wrapAround: true, pageDots: true },
      groupCells: null,
      videos: null,
    };
  },
  computed: {
    copy() {
      return store.copy;
    },
  },
  mounted() {
    this.getFeatured();
    document.body.classList.add('front');
    this.groupCells = this.windowWidth < 840 ? 1 : 2;
    const pageTitle = 'Hammer Channel | Hammer Museum';
    document.title = pageTitle;

    this.$gtm.trackEvent({
      event: 'virtualPageView',
      virtualPageURL: this.$route.fullPath,
      virtualPageTitle: document.title,
    });
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
    seeAllLinkText(count, name) {
      const videos = count > 1 ? 'videos' : 'video';
      // return `See all ${count} ${videos} tagged ${name}`;
      return `See ${count} ${name} ${videos}`;
    },
    viewHandler(e) {
      if (e.percentInView === 1 && e.target.rect.top >= 80) {
        this.currentSectionInView = e.target.element.dataset.sectionId;
        this.$emit('update-current-section', this.currentSectionInView);
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
  margin: 48px 0;
}
</style>

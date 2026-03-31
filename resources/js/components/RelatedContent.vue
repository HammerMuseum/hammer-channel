<template>
  <div class="related-content__wrapper">
    <Carousel
      v-if="items && items.length"
      id="related"
      :key="carouselKey"
      ref="carousel"
      :classes="['carousel--related-content']"
      :options="{}"
      title="Related content"
      :controls="true"
      :show-heading="false"
    >
      <CarouselSlide
        v-for="item in items"
        :key="item.id"
        :item="item"
        heading-type="h3"
        show-date
        class="ui-card--dark-mode"
      />
    </Carousel>
    <VideoMeta>
      <template #highlighted>
        <div
          v-if="tags && tags.length"
          class="ui-table"
        >
          <h4 class="ui-list__title">
            or try
          </h4>
          <ul
            class="ui-list"
          >
            <li
              v-for="item in tags"
              :key="item"
              class="ui-list__item"
            >
              <RouterLink
                :class="['link', 'link--text', 'link--text-secondary']"
                :to="{name: 'search', query: { tags: item } }"
              >
                {{ item }}
              </RouterLink>
            </li>
          </ul>
        </div>
      </template>
    </VideoMeta>
  </div>
</template>

<script>
import Carousel from './Carousel.vue';
import CarouselSlide from './CarouselSlide.vue';
import VideoMeta from './VideoMeta.vue';

export default {
  name: 'RelatedContent',
  components: {
    Carousel,
    CarouselSlide,
    VideoMeta,
  },
  props: {
    items: {
      type: Array,
      default: () => [],
    },
    tags: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      carouselKey: 0,
    };
  },
  watch: {
    items(newVal, oldVal) {
      if (newVal !== oldVal) {
        this.carouselKey += 1;
        this.refreshCarousel();
      }
    },
  },
  methods: {
    refreshCarousel() {
      this.$nextTick(() => {
        if (this.$refs.carousel && this.$refs.carousel.refresh) {
          this.$refs.carousel.refresh();
        }
      });
    },
  },
};
</script>

<style>
.carousel--related-content .carousel__slide {
  width: 75%;
}
</style>

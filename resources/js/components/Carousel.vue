<template>
  <div
    :id="id | filterId"
    class="carousel-wrapper"
  >
    <h2
      :id="headingId |filterId "
      :class="['carousel__title', {'visually-hidden': !showHeading}]"
    >
      {{ title }}
    </h2>
    <div
      v-if="controls"
      class="carousel-controls"
    >
      <button
        type="submit"
        :class="['control', 'control--previous', 'button', 'button--icon', {'button--disabled': currentSlide === 0}]"
        :disabled="currentSlide === 0"
        :aria-disabled="currentSlide === 0"
        @click="$refs.carousel.prev()"
      >
        <svg
          title="Previous"
          class="icon icon--rotate"
        >
          <use xlink:href="/images/sprite.svg#sprite-next" />
        </svg>
        <span class="icon-text visually-hidden">Previous</span>
      </button>
      <button
        type="submit"
        :class="['control', 'control--next', 'button', 'button--icon', {'button--disabled': isFinalSlide}]"
        :disabled="isFinalSlide"
        :aria-disabled="isFinalSlide"
        @click="$refs.carousel.next()"
      >
        <svg
          title="Next"
          class="icon"
        >
          <use xlink:href="/images/sprite.svg#sprite-next" />
        </svg>
        <span class="icon-text visually-hidden">Next</span>
      </button>
    </div>
    <vue-slick-carousel
      ref="carousel"
      :class="['carousel', ...classes]"
      v-bind="settings"
      :aria-labelledby="headingId"
      :arrows="false"
      :dots="false"
      @beforeChange="setCurrentSlide"
      @reInit="setSlideCount"
    >
      <slot />
    </vue-slick-carousel>
  </div>
</template>

<script>
import VueSlickCarousel from 'vue-slick-carousel';

export default {
  components: {
    VueSlickCarousel,
  },
  filters: {
    filterId(value) {
      return value.replace(/[\s&]/gi, '').toLowerCase();
    },
  },
  props: {
    controls: Boolean,
    classes: Array,
    id: String,
    title: String,
    showHeading: {
      default() {
        return true;
      },
      type: Boolean,
    },
    options: Object,
  },
  data() {
    return {
      currentSlide: 0,
      defaultSettings: {
        infinite: false,
        touchThreshold: 5,
      },
      slideCount: null,
    };
  },
  computed: {
    settings() {
      return { ...this.defaultSettings, ...this.options };
    },
    headingId() {
      return `${this.id}heading`;
    },
    isFinalSlide() {
      if (this.slideCount) {
        const sts = this.$refs.carousel.$refs.innerSlider.slidesToShow;
        return this.currentSlide + sts >= this.slideCount;
      }
      return null;
    },
  },
  methods: {
    setSlideCount() {
      this.slideCount = this.$refs.carousel.$refs.innerSlider.slideCount;
    },
    setCurrentSlide(prev, next) {
      this.currentSlide = next;
    },
  },
};
</script>

<style>
@media (max-width: 800px) {
  .carousel-controls {
    display: none;
  }
}

.carousel-controls {
  position: absolute;
  width: 100%;
  top: 50%;
  transform: translateY(-50%);
  z-index: 1;
}

.carousel-controls .control {
  position: absolute;
}

.carousel-controls .control--previous {
  left: 0;
  padding-left: 8px;
}

.carousel-controls .control--next {
  right: 0;
  padding-right: 8px;
}
</style>

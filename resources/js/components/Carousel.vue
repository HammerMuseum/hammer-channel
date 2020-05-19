<template>
  <div
    :id="id | filterId"
  >
    <h2
      :id="headingId |filterId "
      :class="['carousel__title', {'visually-hidden': !showHeading}]"
    >
      {{ title }}
    </h2>
    <div class="carousel-wrapper">
      <div
        v-if="controls"
        ref="controls"
        class="carousel-controls"
      >
        <button
          type="submit"
          :class="['control', 'control--previous', 'button', 'button--icon', {'button--disabled': isFirstSlide}]"
          :disabled="isFirstSlide"
          :aria-disabled="isFirstSlide"
          @click="$refs.carousel.prev()"
        >
          <svg
            title="Previous"
            class="icon icon--rotate"
          >
            <use xlink:href="/images/sprite.svg#sprite-next-with-circle" />
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
            <use xlink:href="/images/sprite.svg#sprite-next-with-circle" />
          </svg>
          <span class="icon-text visually-hidden">Next</span>
        </button>
      </div>
      <VueSlickCarousel
        ref="carousel"
        :class="['carousel', ...classes]"
        v-bind="settings"
        :aria-labelledby="headingId"
        @beforeChange="setCurrentSlide"
        @reInit="reInit"
      >
        <slot />
      </VueSlickCarousel>
    </div>
  </div>
</template>

<script>
import { debounce } from 'lodash';
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
      debouncedSetControlsPosition: null,
      defaultSettings: {
        infinite: false,
        touchThreshold: 5,
        arrows: false,
        dots: false,
      },
      slideCount: 0,
    };
  },
  computed: {
    headingId() {
      return `${this.id}heading`;
    },
    isFinalSlide() {
      if (!this.settings.infinite && this.slideCount) {
        const sts = this.$refs.carousel.$refs.innerSlider.slidesToShow;
        return this.currentSlide + sts >= this.slideCount;
      }
      return false;
    },
    isFirstSlide() {
      return !this.settings.infinite && this.currentSlide === 0;
    },
    settings() {
      return { ...this.defaultSettings, ...this.options };
    },
  },
  mounted() {
    this.debouncedSetControlsPosition = debounce(this.setControlsPosition, 200);
    window.addEventListener('resize', this.debouncedSetControlsPosition, false);
    this.setControlsPosition();
  },
  beforeDestroy() {
    window.addEventListener('resize', this.debouncedSetControlsPosition, false);
  },
  methods: {
    reInit() {
      this.setSlideCount();
      this.setControlsPosition();
    },
    setSlideCount() {
      this.slideCount = this.$refs.carousel.$refs.innerSlider.slideCount;
    },
    setCurrentSlide(prev, next) {
      this.currentSlide = next;
    },
    setControlsPosition() {
      if (!this.$refs.carousel) return;
      const carousel = this.$refs.carousel.$el;
      const itemHeight = carousel.querySelector('.ui-card__thumbnail-image').height;
      const top = itemHeight / 1.4;
      this.$refs.controls.style.top = `${top}px`;
    },
  },
};
</script>

<style>
@media (max-width: 50em) {
  .carousel-controls {
    display: none;
  }
}

.carousel-controls {
  position: absolute;
  width: 100%;
  top: 110px;
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

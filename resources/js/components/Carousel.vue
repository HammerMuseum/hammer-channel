<template>
  <div
    :id="id | filterId"
  >
    <h2
      :id="headingId |filterId "
      :class="['carousel__title', {'visually-hidden': !showHeading}]"
    >
      <slot name="heading" />
    </h2>
    <div class="carousel-wrapper">
      <div
        v-if="controls"
        ref="controls"
        class="carousel-controls"
      >
        <button
          type="submit"
          :class="[
            'control',
            'control--previous',
            'button',
            'button--icon',
            {'button--disabled': isFirstSlide}
          ]"
          :disabled="isFirstSlide"
          :aria-disabled="isFirstSlide"
          @click="$refs.carousel.previous()"
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
          :class="[
            'control',
            'control--next',
            'button',
            'button--icon',
            {'button--disabled': isFinalSlide}
          ]"
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

      <Flickity
        ref="carousel"
        v-images-loaded="imgsLoaded"
        :class="['carousel', ...classes]"
        :aria-labelledby="headingId"
        :options="mergedOptions"
        @init="initCarousel"
      >
        <slot />
      </Flickity>
    </div>
  </div>
</template>

<script>
import { debounce } from 'lodash';
import Flickity from 'vue-flickity';
import imagesLoaded from 'vue-images-loaded';

export default {
  components: {
    Flickity,
  },
  directives: { imagesLoaded },
  filters: {
    filterId(value) {
      return value.replace(/[\s&]/gi, '').toLowerCase();
    },
  },
  props: {
    classes: {
      type: Array,
      default: () => [],
    },
    controls: {
      type: Boolean,
      default: () => true,
    },
    id: {
      type: String,
      default: '',
    },
    options: {
      type: Object,
      default: () => ({}),
    },
    showHeading: {
      type: Boolean,
      default: () => true,
    },
    title: {
      type: String,
      default: '',
    },
  },
  data() {
    return {
      currentSlide: 0,
      totalSlides: 0,
      debouncedSetControlsPosition: null,
      defaultOptions: {
        accessibility: false,
        cellAlign: 'left',
        contain: false,
        freeScroll: true,
        friction: 0.2,
        selectedAttraction: 0.01,
        groupcells: '80%',
        lazyLoad: true,
        pageDots: false,
        percentPosition: true,
        prevNextButtons: false,
        wrapAround: false,
      },
    };
  },
  computed: {
    headingId() {
      return `${this.id}heading`;
    },
    isFinalSlide() {
      if (this.defaultOptions.wrapAround) return false;
      return this.currentSlide === this.totalSlides;
    },
    isFirstSlide() {
      if (this.defaultOptions.wrapAround) return false;
      return this.currentSlide === 0;
    },
    mergedOptions() {
      return { ...this.defaultOptions, ...this.options };
    },
  },
  mounted() {
    this.debouncedSetControlsPosition = debounce(this.setControlsPosition, 200);
    window.addEventListener('resize', this.debouncedSetControlsPosition, false);
  },
  beforeDestroy() {
    window.addEventListener('resize', this.debouncedSetControlsPosition, false);
  },
  methods: {
    imgsLoaded() {
      if (this.$refs.carousel) {
        this.$refs.carousel.reloadCells();
        this.$refs.carousel.resize();
      }
    },
    initCarousel() {
      const carousel = this.$refs.carousel;
      this.totalSlides = carousel.cells().length;

      carousel.on('change', (index) => {
        this.currentSlide = index;
      });

      carousel.on('dragMove', function () {
        this.slider.childNodes.forEach((slide) => {
          slide.style.pointerEvents = 'none';
        });
      });

      carousel.on('dragEnd', function () {
        this.slider.childNodes.forEach((slide) => {
          slide.style.pointerEvents = 'all';
        });
      });

      this.setControlsPosition();
    },
    setControlsPosition() {
      const itemHeight = this.$refs.carousel.$el.querySelector('.ui-card__thumbnail-image').height;
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

.nopointer {
  pointer-events: none;
}
</style>

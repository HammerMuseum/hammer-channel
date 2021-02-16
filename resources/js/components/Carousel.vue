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
    <div :class="['carousel-wrapper', {'carousel--full-width': fullWidth}]">
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
          :aria-disabled="isFirstSlide"
          tabindex="0"
          @click="$refs.carousel.previous()"
        >
          <BaseIcon
            width="36"
            height="36"
            view-box="0 0 36 36"
            icon-name="next-with-circle"
            title="Select previous item"
            :classes="['icon--rotate']"
          >
            <!--  Icon is rotated to point in the correct direction-->
            <NextWithCircleIcon />
          </BaseIcon>
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
          :aria-disabled="isFinalSlide"
          tabindex="0"
          @click="$refs.carousel.next()"
        >
          <BaseIcon
            width="36"
            height="36"
            view-box="0 0 36 36"
            icon-name="next-with-circle"
            title="Select next item"
          >
            <NextWithCircleIcon />
          </BaseIcon>
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
import BaseIcon from './base/BaseIcon.vue';
import NextWithCircleIcon from './icons/NextWithCircleIcon.vue';

export default {
  components: {
    BaseIcon,
    Flickity,
    NextWithCircleIcon,
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
    fullWidth: {
      type: Boolean,
      default: false,
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
        freeScroll: false,
        friction: 0.25,
        selectedAttraction: 0.02,
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
      let total = this.totalSlides;
      const group = this.options.groupCells;
      if (group && group > 1) {
        total = this.totalSlides / group;
      }
      return !this.mergedOptions.wrapAround && this.currentSlide === total;
    },
    isFirstSlide() {
      return !this.mergedOptions.wrapAround && this.currentSlide === 0;
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
    handleCarouselCellFocus(carousel) {
      carousel.cells().slice(0, carousel.cells().length - 1).forEach((el) => {
        el.element.querySelector('a').setAttribute('tabindex', '-1');
      });
      carousel.selectedElements().forEach((el) => {
        el.querySelector('a').setAttribute('tabindex', '0');
      });
    },
    imgsLoaded() {
      if (this.$refs.carousel) {
        this.$refs.carousel.reloadCells();
        this.$refs.carousel.resize();
      }
    },
    initCarousel() {
      const carousel = this.$refs.carousel;
      this.totalSlides = carousel.cells().length - 1;
      this.handleCarouselCellFocus(carousel);

      carousel.on('change', (index) => {
        this.currentSlide = index;
        this.handleCarouselCellFocus(carousel);
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
      if (this.$refs.carousel) {
        let top = 0;
        if (this.id === 'featured') {
          const carouselHeight = this.$refs.carousel.$el.offsetHeight;
          // Half-way down minus half the height of the buttons
          top = carouselHeight / 2 - 16;
        } else {
          const imageHeight = this.$refs.carousel.$el.querySelector('.ui-card__thumbnail-image').height;
          top = imageHeight / 1.4;
        }

        this.$refs.controls.style.top = `${top}px`;
      }
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

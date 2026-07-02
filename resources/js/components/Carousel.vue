<template>
  <div :id="filterId(id)">
    <h2
      v-if="showHeading"
      :id="filterId(headingId)"
      :class="['carousel__title']"
    >
      <slot name="heading" />
    </h2>
    <div :class="['carousel-wrapper', { 'carousel--full-width': fullWidth }]">
      <div
        v-if="controls && hasImagesLoaded"
        ref="controls"
        class="carousel-controls"
      >
        <button
          type="button"
          :class="[
            'control',
            'control--previous',
            'button',
            'button--icon',
            { 'button--disabled': isFirstSlide },
          ]"
          :aria-disabled="isFirstSlide"
          tabindex="-1"
          @click.stop.prevent="onPrevClick"
          @mousedown.stop.prevent
        >
          <BaseIcon
            width="36"
            height="36"
            view-box="0 0 36 36"
            title="Select previous item"
            :icon-name="`${id}-previous-with-circle`"
            :classes="['icon--rotate']"
          >
            <!--  Icon is rotated to point in the correct direction-->
            <NextWithCircleIcon />
          </BaseIcon>
        </button>
        <button
          type="button"
          :class="[
            'control',
            'control--next',
            'button',
            'button--icon',
            { 'button--disabled': isFinalSlide },
          ]"
          :aria-disabled="isFinalSlide"
          tabindex="-1"
          @click.stop.prevent="onNextClick"
          @mousedown.stop.prevent
        >
          <BaseIcon
            width="36"
            height="36"
            view-box="0 0 36 36"
            title="Select next item"
            :icon-name="`${id}-next-with-circle`"
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
import debounce from 'lodash/debounce';
import imagesLoadedDirective from '../directives/imagesLoaded';
import BaseIcon from './base/BaseIcon.vue';
import NextWithCircleIcon from './icons/NextWithCircleIcon.vue';
import { filterId } from '../filters';

export default {
  components: {
    BaseIcon,
    NextWithCircleIcon,
  },
  directives: {
    imagesLoaded: imagesLoadedDirective,
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
      carouselLinks: [],
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
      observer: null,
      isFinalSlideVisible: false,
      hasImagesLoaded: false,
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
      return (
        !this.mergedOptions.wrapAround
        && (this.currentSlide === total || this.isFinalSlideVisible)
      );
    },
    isFirstSlide() {
      return !this.mergedOptions.wrapAround && this.currentSlide === 0;
    },
    mergedOptions() {
      return { ...this.defaultOptions, ...this.options };
    },
  },
  mounted() {
    this.setupObserver();
    this.debouncedSetControlsPosition = debounce(this.setControlsPosition, 200);
    window.addEventListener('resize', this.debouncedSetControlsPosition, false);
  },
  beforeUnmount() {
    this.observer.disconnect();
    window.removeEventListener('resize', this.debouncedSetControlsPosition, false);
  },
  methods: {
    filterId,
    imgsLoaded() {
      if (this.$refs.carousel) {
        this.$refs.carousel.reloadCells();
        this.$refs.carousel.resize();
        this.setControlsPosition();
      }
      this.hasImagesLoaded = true;
    },
    onPrevClick() {
      if (this.$refs.carousel) {
        this.$refs.carousel.previous();
      }
    },
    onNextClick() {
      if (this.$refs.carousel) {
        this.$refs.carousel.next();
      }
    },
    refresh() {
      if (this.$refs.carousel) {
        this.$refs.carousel.reloadCells();
        this.$refs.carousel.resize();
        this.setControlsPosition();
      }
    },
    initCarousel() {
      const carousel = this.$refs.carousel;
      this.totalSlides = carousel.cells().length - 1;

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

      this.carouselLinks = this.$refs.carousel.$el.querySelectorAll('a');

      this.$refs.carousel.$el.addEventListener('keydown', (event) => {
        let targetLink = null;

        // Only listen for Tab key presses
        if (event.which !== 9) {
          return;
        }
        // Get curently focused element
        const focusedElement = event.target;
        if (!focusedElement) {
          return;
        }

        // Find the currently focused element in the array of links
        const selectedLinkIndex = [...this.carouselLinks].indexOf(
          focusedElement,
        );
        if (selectedLinkIndex === -1) {
          return;
        }

        if (!event.shiftKey && this.carouselLinks[selectedLinkIndex + 1]) {
          targetLink = this.carouselLinks[selectedLinkIndex + 1];
        } else if (
          event.shiftKey
          && this.carouselLinks[selectedLinkIndex - 1]
        ) {
          targetLink = this.carouselLinks[selectedLinkIndex - 1];
        }

        // Check whether
        // a) 'element.closest' is supported and
        // b) the target link is inside a carousel slide
        const parentSlide = Element.prototype.closest && targetLink
          ? targetLink.closest('.carousel__slide')
          : null;

        if (parentSlide) {
          event.preventDefault();
          // Select the slide by element so Flickity resolves the correct
          // group. Passing a cell index to select() breaks when groupCells
          // (and contain) are set, as cell index !== group index.
          this.$refs.carousel.selectCell(parentSlide, false, true);
          targetLink.focus();
        }
      });

      this.setControlsPosition();
    },
    setControlsPosition() {
      if (!this.$refs.carousel || !this.$refs.controls) {
        return;
      }

      let top = 0;
      if (this.id === 'featured') {
        const carouselHeight = this.$refs.carousel.$el.offsetHeight;
        // Half-way down minus half the height of the buttons
        top = carouselHeight / 2 - 16;
      } else {
        const image = this.$refs.carousel.$el.querySelector(
          '.ui-card__thumbnail-image',
        );

        // Make sure images have loaded before calculating pos of controls
        if (!image) {
          return;
        }
        const imageHeight = image.height;
        top = imageHeight / 1.4;
      }

      this.$refs.controls.style.top = `${top}px`;
    },
    setupObserver() {
      const finalSlide = this.$refs.carousel.$el.querySelector(
        '.carousel__slide:last-child',
      );
      this.observer = new IntersectionObserver(this.observerCallback, {
        threshold: [1],
      });
      this.observer.observe(finalSlide);
    },
    observerCallback(e) {
      this.isFinalSlideVisible = e[0].intersectionRatio === 1;
    },
  },
};
</script>

<style>
.carousel-controls {
  position: absolute;
  width: 100%;
  top: 110px;
  z-index: 20;
}

.carousel-controls .control {
  position: absolute;
}

.control[aria-disabled="true"] {
  pointer-events: none;
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

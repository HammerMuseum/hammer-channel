<template>
  <div
    :class="navigationClasses"
  >
    <Flickity
      v-if="items && Object.keys(items).length > 0"
      ref="flickity"
      :options="flickityOptions"
      role="navigation"
      aria-label="List of video topics"
      class="navigation-bar__inner"
      @init="initNavigationBar"
    >
      <div
        v-for="({id, label}) in items"
        :key="id"
        :data-selector="id"
        class="navigation-bar__item"
      >
        <a
          href="#"
          :aria-label="`Topic ${label}`"
          :class="['link', {'link--active': activeItem === id }]"
          @click.prevent="scrollTo(id)"
        >{{ label }}</a>
      </div>
      <div
        class="navigation-bar__item"
      >
        <a
          v-scroll-to="{ el: `body`, duration: 0, offset: -80 }"
          href="#"
          :class="['link']"
        >Back to top
          <svg
            class="icon icon--nav-bar-link"
          >
            <use xlink:href="/images/sprite.svg#sprite-next" />
          </svg>
        </a>
      </div>
    </Flickity>
  </div>
</template>

<script>
import Flickity from 'vue-flickity';

export default {
  name: 'NavigationBar',
  components: {
    Flickity,
  },
  filters: {
    filterId(value) {
      return value.replace(/[\s&]/gi, '').toLowerCase();
    },
    anchorLink(value) {
      return `#${value}`;
    },
  },
  props: {
    items: {
      type: Array,
      default: () => [],
    },
    activeItem: {
      type: String,
      default: () => '',
    },
    classes: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      flickityOptions: {
        freeScroll: true,
        wrapAround: false,
        contain: false,
        prevNextButtons: false,
        pageDots: false,
        cellAlign: 'left',
        accessibility: false,
      },
    };
  },
  computed: {
    navigationClasses() {
      return ['navigation-bar', ...this.classes];
    },
  },
  watch: {
    activeItem(item) {
      if (item) {
        this.selectNavigationItem(item);
      }
    },
  },
  methods: {
    scrollTo(id) {
      const offset = ((window.innerHeight / 2) * -1);
      this.$scrollTo(`#${id}`, 0, { offset });
    },
    selectNavigationItem(item) {
      this.$refs.flickity.selectCell(`[data-selector="${item}"]`, false);
    },
    initNavigationBar() {
      const self = this;

      this.$refs.flickity.on('staticClick', (event, pointer, cellElement, cellIndex) => {
        if (typeof cellIndex === 'number') {
          self.$refs.flickity.selectCell(cellIndex);
        }
      });

      setTimeout(() => {
        this.$refs.flickity.resize();
      }, 1000);
    },
  },
};
</script>

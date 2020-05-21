<template>
  <div
    :class="navigationClasses"
  >
    <Flickity
      v-if="items && Object.keys(items).length > 0"
      ref="flickity"
      :options="flickityOptions"
      role="navigation"
      aria-label="Video topics"
      @init="initNavigationBar"
    >
      <div
        v-for="(item, name) in items"
        :key="item.id"
        :data-selector="item.id"
        class="navigation-bar__item"
      >
        <a
          v-scroll-to="{ el: `#${item.id}`, duration: 0, offset: -80 }"
          href="#"
          :class="['link', {'link--active': activeItem === item.id }]"
        >{{ name }}</a>
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
            title="Back to top"
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
      type: Object,
      default() {
        return {};
      },
    },
    activeItem: {
      type: String,
      default() {
        return '';
      },
    },
    classes: {
      type: Array,
      default() {
        return [];
      },
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
    selectNavigationItem(item) {
      this.$refs.flickity.selectCell(`[data-selector="${item}"]`, false);
    },
    initNavigationBar() {
      const FLICKITY_EVENTS = [
        'change',
        'select',
        'staticClick',
      ];

      const self = this;
      // events
      const events = FLICKITY_EVENTS;
      // watch events
      const onEdEvents = {};
      for (let i = 0; i < events.length; i += 1) {
        if (typeof events[i] === 'string' && onEdEvents[events[i]] === undefined) {
          ((event) => {
            onEdEvents[event] = null;
            this.$refs.flickity.on(event, (...args) => {
              self.$emit(event, args);
            });
          })(events[i]);
        }
      }

      this.$refs.flickity.on('staticClick', (event, pointer, cellElement, cellIndex) => {
        if (typeof cellIndex === 'number') {
          self.$refs.flickity.selectCell(cellIndex);
        }
      });
    },
  },
};
</script>

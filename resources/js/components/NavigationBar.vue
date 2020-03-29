<template>
  <flickity
    v-if="items && Object.keys(items).length > 0"
    ref="flickity"
    :class="navigationClasses"
    :options="flickityOptions"
    @init="initNavigationBar"
  >
    <div
      v-for="(item, name) in items"
      :key="item.id"
      :data-selector="item.id"
      class="navigation-bar__item"
    >
      <a
        :href="item.id | anchorLink"
        :class="['link', {'link--active': activeItem === item.id }]"
      >{{ name }}</a>
    </div>
  </flickity>
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
        wrapAround: true,
        contain: false,
        prevNextButtons: false,
        pageDots: false,
        cellAlign: 'left',
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
      this.$refs.flickity.selectCell(`[data-selector="${item}"]`, true);
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

<template>
  <flickity
    v-if="items && Object.keys(items).length > 0"
    ref="flickity"
    class="topic-menu scrolling-menu"
    :options="flickityOptions"
    @init="initNavigationBar"
  >
    <div
      v-for="(idx, item) in items"
      :key="item"
      class="scrolling-menu__item"
    >
      <a
        :href="item | filterId | anchorLink"
        class="topic__link"
      >{{ item }}</a>
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
  },
  data() {
    return {
      flickityOptions: {
        freeScroll: true,
        contain: false,
        prevNextButtons: false,
        pageDots: false,
        cellAlign: 'left',
      },
    };
  },
  methods: {
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

<style>
.topic-menu {
  position: fixed;
  background: #fff;
  bottom: 0;
  left: 0;
  width: 100%;
  z-index: 1;
  font-size: 24px;
  padding-left: 20px;
}

.scrolling-menu::after {
  content: "";
  height: 100%;
  position: absolute;
  width: 150px;
  top: 0;
  right: 0;
  background: linear-gradient(270deg, rgb(255, 255, 255) 0%, rgba(255,255,255,0) 100%);
}

.scrolling-menu__item {
  padding: 14px 0;
  margin: 0 32px;
  white-space: nowrap;
}

.topic__link {
  text-decoration: none;
  color: #4d4b4d;
}

.topic__link--active {
  font-weight: 600;
  color: pink;
}
</style>

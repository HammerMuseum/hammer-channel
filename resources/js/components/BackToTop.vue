<template>
  <transition name="fade">
    <button
      v-show="visible"
      :class="classNames"
      :aria-label="label"
      @click="scrollTop"
    >
      <slot />
    </button>
  </transition>
</template>

<script>
import VueScrollTo from 'vue-scrollto';
import { throttle } from 'lodash';

export default {
  name: 'BackToTop',
  props: {
    classes: {
      type: Array,
      default: () => [],
    },
    container: {
      type: String,
      default: 'body',
    },
    element: {
      type: String,
      default: 'body',
    },
    label: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      initialEl: null,
      throttledScrollListener: null,
      visible: false,
    };
  },
  computed: {
    classNames() {
      return ['button', 'button--to-top', ...this.classes];
    },
    options() {
      return {
        container: this.container,
        easing: 'ease-in',
      };
    },
  },
  watch: {
    container() {
      this.eventEl.removeEventListener('scroll', this.throttledScrollListener);
      this.eventEl = this.container === 'body' ? window : document.querySelector(this.container);
      this.eventEl.addEventListener('scroll', this.throttledScrollListener);
    },
  },
  mounted() {
    this.throttledScrollListener = throttle(this.scrollListener, 1000);
    // If no container element specified then add listener to window.
    this.eventEl = this.container === 'body' ? window : document.querySelector(this.container);
    this.eventEl.addEventListener('scroll', this.throttledScrollListener);
  },
  beforeDestroy() {
    this.eventEl.removeEventListener('scroll', this.throttledScrollListener);
  },
  methods: {
    scrollTop() {
      const el = this.element;
      VueScrollTo.scrollTo(el, 600, this.options);
    },
    scrollListener() {
      if (this.container === 'body') {
        this.visible = window.pageYOffset > 150;
      } else {
        this.visible = document.querySelector(this.container).scrollTop > 150;
      }
    },
  },
};
</script>

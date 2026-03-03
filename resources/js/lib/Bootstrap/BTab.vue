<template>
  <div
    :class="['tab-pane', tabClass, { active: isActive, show: isActive }]"
    role="tabpanel"
  >
    <!--
      Lazy behaviour: only render slot content once the tab has been
      visited at least once. This prevents child components (e.g. Transcript,
      BackToTop) from mounting and querying the DOM before they are visible.
    -->
    <slot v-if="shouldRender" />
  </div>
</template>

<script>
export default {
  name: 'BTab',
  props: {
    active: {
      type: Boolean,
      default: false,
    },
    tabClass: {
      type: String,
      default: '',
    },
  },
  emits: ['click'],
  data() {
    return {
      index: -1,
      hasBeenActive: false,
    };
  },
  computed: {
    isActive() {
      if (!this.$parent) return false;
      return this.$parent.activeIndex === this.index;
    },
    parentLazy() {
      return !!(this.$parent && this.$parent.lazy);
    },
    shouldRender() {
      // If the parent is not lazy, always render.
      // If lazy, only render once the tab has been activated at least once.
      if (!this.parentLazy) return true;
      return this.hasBeenActive;
    },
  },
  watch: {
    isActive(val) {
      if (val && !this.hasBeenActive) {
        this.hasBeenActive = true;
      }
    },
  },
  mounted() {
    const parent = this.$parent;
    if (parent && parent.registerTab) {
      parent.registerTab(this);
      this.index = parent.tabs.indexOf(this);

      // If this tab carries the `active` prop, tell the parent to activate it
      if (this.active) {
        parent.setActiveIndex(this.index);
      }

      // Mark as visited if it is already the active tab on mount
      if (this.isActive) {
        this.hasBeenActive = true;
      }
    }
  },
  beforeUnmount() {
    const parent = this.$parent;
    if (parent && parent.unregisterTab) {
      parent.unregisterTab(this);
    }
  },
};
</script>

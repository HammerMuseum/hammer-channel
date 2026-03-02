<template>
  <div :class="['tabs', staticClass]">
    <ul
      class="nav nav-tabs"
      role="tablist"
    >
      <li
        v-for="(tab, index) in tabs"
        :key="index"
        class="nav-item"
        role="presentation"
      >
        <a
          href="#"
          class="nav-link"
          :class="{ active: activeIndex === index }"
          role="tab"
          :aria-selected="activeIndex === index"
          @click.prevent="selectTab(index)"
        >
          <tab-title-renderer :tab="tab" />
        </a>
      </li>
    </ul>
    <div class="tab-content">
      <slot />
    </div>
  </div>
</template>

<script>
const TabTitleRenderer = {
  name: 'TabTitleRenderer',
  props: {
    tab: {
      type: Object,
      default: null,
    },
  },
  render() {
    if (this.tab && this.tab.$slots && this.tab.$slots.title) {
      return this.tab.$slots.title();
    }
    return null;
  },
};

export default {
  name: 'BTabs',

  components: { TabTitleRenderer },

  props: {
    modelValue: {
      type: Number,
      default: 0,
    },
    lazy: {
      type: Boolean,
      default: false,
    },
    staticClass: {
      type: String,
      default: '',
    },
  },

  emits: ['update:modelValue'],

  data() {
    return {
      tabs: [],
      activeIndex: this.modelValue,
    };
  },

  watch: {
    modelValue(val) {
      this.activeIndex = val;
    },
  },

  methods: {
    selectTab(index) {
      this.activeIndex = index;
      this.$emit('update:modelValue', index);

      // Fire the click handler on the BTab child so @click="jumpToLowerPanel" still works
      const tab = this.tabs[index];
      if (tab) {
        tab.$emit('click');
      }
    },

    setActiveIndex(index) {
      this.activeIndex = index;
      this.$emit('update:modelValue', index);
    },

    registerTab(tab) {
      this.tabs.push(tab);
    },

    unregisterTab(tab) {
      const index = this.tabs.indexOf(tab);
      if (index > -1) {
        this.tabs.splice(index, 1);
      }
    },
  },
};
</script>

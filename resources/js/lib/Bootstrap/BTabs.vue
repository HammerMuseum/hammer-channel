<!-- eslint-disable vuejs-accessibility/anchor-has-content -->
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
          :ref="el => { if (el) tabRefs[index] = el }"
          href="#"
          class="nav-link"
          :class="{ active: activeIndex === index }"
          role="tab"
          :aria-selected="activeIndex === index"
          :tabindex="activeIndex !== index ? -1 : 0"
          target="_self"
          @click.prevent="selectTab(index)"
          @keydown="onKeydown($event, index)"
        >
          <TabTitleRenderer :tab="tab" />
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
      tabRefs: [],
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
    onKeydown(event, index) {
      let newIndex = null;
      if (event.key === 'ArrowRight') {
        newIndex = (index + 1) % this.tabs.length;
      } else if (event.key === 'ArrowLeft') {
        newIndex = (index - 1 + this.tabs.length) % this.tabs.length;
      }

      if (newIndex !== null) {
        event.preventDefault();
        this.selectTab(newIndex);
        this.$nextTick(() => {
          this.tabRefs[newIndex]?.focus();
        });
      }
    },
  },
};
</script>

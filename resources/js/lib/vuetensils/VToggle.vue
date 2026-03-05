<template>
  <div :class="['vts-toggle', { 'vts-toggle--open': isOpen }, classes.root]">
    <button
      :id="`${id}-label`"
      ref="label"
      type="button"
      :aria-controls="`${id}-content`"
      :aria-expanded="isOpen"
      :class="['vts-toggle__label', classes.label]"
      @click="isOpen = !isOpen"
      v-on="listeners"
    >
      <slot
        name="label"
        v-bind="{ isOpen }"
      />
    </button>

    <transition
      @before-enter="collapse"
      @enter="expand"
      @after-enter="resetHeight"
      @before-leave="expand"
      @leave="collapse"
    >
      <div
        v-show="isOpen"
        :id="`${id}-content`"
        :aria-labelledby="`${id}-label`"
        :aria-hidden="!isOpen"
        role="region"
        :class="['vts-toggle__content', classes.content]"
      >
        <slot v-bind="{ isOpen }" />
      </div>
    </transition>
  </div>
</template>

<script>
export default {
  name: 'VToggle',
  props: {
    open: {
      type: Boolean,
      default: false,
    },
    classes: {
      type: Object,
      default: () => ({}),
    },
  },
  emits: ['update', 'open', 'close'],
  data() {
    return {
      isOpen: this.open,
      id: '',
    };
  },
  computed: {
    listeners() {
      // Forward only event listeners from $attrs (keys starting with "on")
      // so we don't accidentally treat plain attributes as handlers.
      const entries = Object.entries(this.$attrs).filter(
        ([key]) => key.startsWith('on'),
      );
      return Object.fromEntries(entries);
    },
  },
  watch: {
    open(next) {
      this.isOpen = next;
    },
    isOpen(isOpen) {
      this.$emit('update', isOpen);
      this.$emit(isOpen ? 'open' : 'close');
    },
  },
  created() {
    const rawId = this.$attrs.id;
    this.id = rawId ? String(rawId) : `vts-${Math.random().toString(36).slice(2, 6)}`;
  },
  methods: {
    collapse(el) {
      el.style.blockSize = '0';
    },
    expand(el) {
      el.style.overflow = 'hidden';
      el.style.blockSize = `${el.scrollHeight}px`;
      // Force repaint
      // eslint-disable-next-line no-unused-expressions
      el.scrollHeight;
    },
    resetHeight(el) {
      el.style.overflow = 'visible';
      el.style.blockSize = '';
    },
  },
};
</script>

<style>
.vts-toggle__content {
  transition: 300ms ease block-size;
}
</style>

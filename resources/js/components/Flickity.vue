<!-- Custom wrapper for flickity module -->
<template>
  <div v-bind="$attrs">
    <slot />
  </div>
</template>

<script>
import Flickity from 'flickity';

export default {
  name: 'Flickity',
  inheritAttrs: false,
  props: {
    options: {
      type: Object,
      default: () => ({}),
    },
  },
  data() {
    return {
      flkty: null,
    };
  },
  mounted() {
    this.flkty = new Flickity(this.$el, this.options);
    this.$emit('init');
  },
  beforeUnmount() {
    if (this.flkty) {
      this.flkty.destroy();
      this.flkty = null;
    }
  },
  methods: {
    next() {
      if (this.flkty) this.flkty.next();
    },
    previous() {
      if (this.flkty) this.flkty.previous();
    },
    select(index, isWrapped, isInstant) {
      if (this.flkty) this.flkty.select(index, isWrapped, isInstant);
    },
    selectCell(cell, isWrapped) {
      if (this.flkty) this.flkty.selectCell(cell, isWrapped);
    },
    resize() {
      if (this.flkty) this.flkty.resize();
    },
    reloadCells() {
      if (this.flkty) this.flkty.reloadCells();
    },
    on(...args) {
      if (this.flkty) this.flkty.on(...args);
    },
    cells() {
      return this.flkty ? this.flkty.cells : [];
    },
  },
};
</script>

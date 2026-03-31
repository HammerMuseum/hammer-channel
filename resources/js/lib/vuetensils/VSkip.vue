<template>
  <a
    class="vts-skip"
    :href="to"
    v-bind="$attrs"
    @click.prevent="handleClick"
  >
    <slot>Skip to main content</slot>
  </a>
</template>

<script>
export default {
  name: 'VSkip',
  inheritAttrs: true,
  props: {
    to: {
      type: String,
      required: true,
    },
  },
  methods: {
    handleClick() {
      const id = this.to;
      if (!id) return;

      const target = window.document.getElementById(id.slice(1));
      if (!target) return;

      if (
        !['a', 'select', 'input', 'button', 'textarea'].includes(
          target.tagName.toLowerCase(),
        )
      ) {
        // Make non-interactive targets temporarily focusable
        target.setAttribute('tabindex', '-1');
      }
      target.focus();
    },
  },
};
</script>

<style>
.vts-skip {
  position: fixed;
  z-index: 1000;
  inset-block-start: 0;
  inset-inline-start: -10000px;
  border: 3px solid;
  padding: 0.5rem;
  color: #000;
  background-color: #fff;
}

.vts-skip:focus {
  inset-inline-start: 0;
}
</style>

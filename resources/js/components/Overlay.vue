<template>
  <div
    class="overlay"
    @click.self="close"
  >
    <div
      class="overlay__body"
    >
      <FocusTrap
        :active="true"
        :escape-deactivates="true"
      >
        <div
          class="overlay__inner"
        >
          <button
            ref="close"
            class="button button--icon overlay__close-button"
            aria-label="Close filter options"
            @click.stop="close"
          >
            <svg class="icon icon--close">
              <use xlink:href="/images/sprite.svg#sprite-close" />
            </svg>
          </button>
          <slot />
        </div>
      </FocusTrap>
    </div>
  </div>
</template>

<script>
import { FocusTrap } from 'focus-trap-vue';

export default {
  name: 'Overlay',
  components: {
    FocusTrap,
  },
  mounted() {
    document.addEventListener('keyup', this.onEscape);
    this.$refs.close.focus();
  },
  destroyed() {
    document.removeEventListener('keyup', this.onEscape);
  },
  methods: {
    onEscape(event) {
      event.stopPropagation();
      if (event.which === 27) {
        this.close();
      }
    },
    close() {
      this.$emit('close-panel');
    },
  },
};
</script>

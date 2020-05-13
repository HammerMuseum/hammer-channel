<template>
  <div
    class="overlay"
    tabindex="0"
  >
    <div
      v-clickout="closeOverlayPanel"
      class="overlay__body"
    >
      <div
        class="overlay__inner"
        @keyup.escape.stop="closeOverlayPanel"
      >
        <button
          class="button button--icon overlay__close-button"
          aria-label="Close"
          @click.prevent="closeOverlayPanel"
        >
          <svg class="icon icon--close">
            <use xlink:href="/images/sprite.svg#sprite-close" />
          </svg>
        </button>
        <slot />
      </div>
    </div>
  </div>
</template>

<script>
import { clickout } from 'vuetensils';

export default {
  name: 'Overlay',
  directives: {
    clickout,
  },
  methods: {
    closeOverlayPanel() {
      this.$emit('closePanel');
    },
  },
};
</script>

<template>
  <div
    class="overlay"
    tabindex="0"
  >
    <div
      v-click-outside="closeOverlayPanel"
      class="overlay__body"
    >
      <FocusTrap
        :active="true"
        :escape-deactivates="false"
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
  methods: {
    closeOverlayPanel() {
      this.$emit('close-panel');
    },
  },
};
</script>

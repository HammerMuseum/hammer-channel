<template>
  <div
    class="overlay"
    tabindex="0"
  >
    <div
      v-on-clickaway="closeOverlayPanel"
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
import { mixin as clickaway } from 'vue-clickaway';
import { FocusTrap } from 'focus-trap-vue';

export default {
  name: 'Overlay',
  components: {
    FocusTrap,
  },
  mixins: [
    clickaway,
  ],
  methods: {
    closeOverlayPanel() {
      this.$emit('closePanel');
    },
  },
};
</script>

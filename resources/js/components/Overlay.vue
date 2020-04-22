<template>
  <div
    class="overlay"
    tabindex="0"
  >
    <div class="overlay__body">
      <div class="overlay__inner">
        <button
          class="button button--icon overlay__close-button"
          aria-label="Close"
          @click.prevent="$emit('closePanel')"
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
export default {
  name: 'Overlay',
};
</script>

<style>

</style>

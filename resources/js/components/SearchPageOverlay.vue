<template>
  <div
    class="search-page__overlay"
    tabindex="0"
  >
    <div class="canvas-overlay" />
    <div class="search-page__overlay__body">
      <div class="search-page__overlay__inner">
        <button
          class="button button--icon search-page__overlay__close-button"
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
  name: 'SearchPageOverlay',
};
</script>

<style>

</style>

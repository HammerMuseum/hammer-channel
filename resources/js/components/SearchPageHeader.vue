<template>
  <div class="search-page__header">
    <h1 class="page-heading">
      Search results
    </h1>
    <slot />
    <VToggle
      label="Sort by"
      transition="slide-down"
      class="search-page__sorting-control"
    >
    <template #label="{ isOpen }">
      <span aria-hidden>
        <svg
          title="Search"
          class="icon"
        >
          <use xlink:href="/images/sprite.svg#sprite-search" />
        </svg>
        {{ isOpen ? "🔼" : "🔽" }}</span>
      Sort by
    </template>

    <template #default="{isOpen}">isOpen: {{ isOpen }}</template>

    </VToggle>
  </div>
</template>
<script>
import { VToggle } from 'vuetensils';

export default {
  components: {
    VToggle,
  },
};
</script>

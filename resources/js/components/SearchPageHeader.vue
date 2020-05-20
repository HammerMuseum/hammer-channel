<template>
  <header class="search-header">
    <div class="search-header__inner search-header__inner-layout">
      <h1 :class="headingClasses">
        Search results
      </h1>
      <div
        v-show="!loading"
        class="search-header__summary"
      >
        <slot name="summary" />
      </div>
      <div class="search-header__extras">
        <slot name="extras" />
      </div>
    </div>
    <div class="search-header__inner">
      <slot />
    </div>
  </header>
</template>

<script>
export default {
  name: 'SearchPageHeader',
  props: {
    extraClasses: {
      type: Array,
      default() {
        return [];
      },
    },
    loading: {
      type: Boolean,
      required: true,
    },
  },
  data() {
    return {
      classes: ['search-header__heading'],
    };
  },
  computed: {
    headingClasses() {
      return [this.classes, ...this.extraClasses];
    },
  },
};
</script>

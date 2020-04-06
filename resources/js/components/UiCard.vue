<template>
  <div :class="classes">
    <slot />
  </div>
</template>

<script>
export default {
  name: 'UiCard',
  props: {
    extraClasses: {
      type: Array,
      default() {
        return [];
      },
    },
  },
  computed: {
    classes() {
      return ['ui-card', ...this.extraClasses];
    },
  },
};
</script>

<style>
.ui-card {
  display: flex;
  flex-direction: column;
  position: relative;
  padding-right: 24px;
}

.ui-card > a {
  text-decoration: none;
}

.ui-card:focus {
  background-color: initial;
}
</style>

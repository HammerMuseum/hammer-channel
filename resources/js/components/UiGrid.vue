<template>
  <div class="ui-grid">
    <slot />
  </div>
</template>

<script>
export default {
  name: 'UiGrid',
};
</script>

<style>
.ui-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  grid-gap: 24px;
}
</style>

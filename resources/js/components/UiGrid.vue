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
  grid-gap: 24px;
  grid-template-columns: repeat(1, 1fr);
}

@media (min-width: 480px) {
  .ui-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (min-width: 1353px) {
  .ui-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}
</style>

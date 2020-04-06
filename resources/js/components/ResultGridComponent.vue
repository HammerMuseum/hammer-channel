<template>
  <div class="result-grid">
    <div
      v-for="item in items"
      :key="item.title_slug"
      class="result-item"
    >
      <span class="video__duration">{{ item.duration }}</span>
      <router-link
        :to="{name: 'video', params: {id: item.title_slug}}"
      >
        <img
          class="result-item__image"
          :src="`${ item.thumbnail_url }`"
        >
        <span class="result-item__title">{{ item.title }}</span>
      </router-link>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ResultGrid',
  props: {
    items: Array,
  },
};
</script>

<template>
  <router-link
    :to="{name: 'video', params: {id: slug }}"
    class="carousel-slide"
  >
    <div class="video__thumbnail">
      <span class="video__duration">{{ duration }}</span>
      <img
        :src="thumbnailUrl"
        class="video__thumbnail-image"
      >
    </div>
    <div class="video__title">
      <span>{{ title }}</span>
    </div>
  </router-link>
</template>

<script>
export default {
  props: {
    item: {
      type: Object,
      default() {
        return {};
      },
    },
  },
  computed: {
    duration() {
      return this.item.duration;
    },
    slug() {
      return this.item.title_slug;
    },
    thumbnailUrl() {
      return this.item.thumbnail_url;
    },
    title() {
      return this.item.title;
    },
  },
};
</script>

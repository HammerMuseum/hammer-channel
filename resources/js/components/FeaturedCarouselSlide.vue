<template>
  <router-link
    :to="{name: 'video', params: {id: slug}}"
    class="carousel-slide carousel-slide--featured"
  >
    <div class="video__thumbnail">
      <span class="video__duration">{{ duration }}</span>
      <img
        class="video__thumbnail-image"
        :src="thumbnailUrl"
      >
    </div>
    <div class="video__info">
      <template v-if="quote">
        <q class="video__info-title">{{ quote }}</q>
        <div class="video__info-teaser">
          <div class="video__info-subtitle">
            {{ title }}
          </div>
        </div>
      </template>
      <template v-else>
        <span class="video__info-title">{{ title }}</span>
        <div class="video__info-teaser">
          <div class="video__info-subtitle">
            {{ subtitle }}
          </div>
          {{ description }}
        </div>
      </template>
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
    description() {
      return this.item.description.substr(0, 200);
    },
    duration() {
      return this.item.duration;
    },
    quote() {
      return this.item.quote;
    },
    slug() {
      return this.item.title_slug;
    },
    subtitle() {
      return this.item.subtitle;
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
<style>
.carousel-slide {
  text-decoration: none;
}
</style>

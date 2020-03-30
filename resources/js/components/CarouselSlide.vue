<template>
  <content-loader
    v-if="!item"
    :speed="2"
    :animate="true"
  />
  <div
    v-else
    class="carousel__slide"
  >
    <router-link
      :to="{name: 'video', params: {id: slug }}"
      class="video-card"
    >
      <div class="video-card__thumbnail">
        <span class="video-card__duration">{{ duration }}</span>
        <img
          :src="thumbnailUrl"
          class="video-card__thumbnail-image"
        >
      </div>
      <article>
        <h2 class="video-card__title">
          <span>{{ title }}</span>
        </h2>
        <p class="video-card__description">
          {{ description }}
        </p>
      </article>
    </router-link>
  </div>
</template>

<script>
import { truncate } from 'lodash';
import { ContentLoader } from 'vue-content-loader';

export default {
  components: {
    ContentLoader,
  },
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
      return truncate(this.item.description, {
        length: 100,
      });
    },
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

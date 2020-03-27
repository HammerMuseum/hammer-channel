<template>
  <div class="carousel__slide carousel__slide--featured">
    <router-link
      :to="{name: 'video', params: {id: slug}}"
      class="video-card video-card--featured"
    >
      <div class="video-card__thumbnail">
        <span class="video-card__duration">{{ duration }}</span>
        <img
          class="video-card__thumbnail-image"
          :src="thumbnailUrl"
        >
      </div>
      <article class="video-card__info">
        <template v-if="quote">
          <q class="video-card__quote">{{ quote }}</q>
          <div class="video-card__teaser">
            <h2 class="video-card__title">
              {{ title }}
            </h2>
          </div>
        </template>
        <template v-else>
          <span class="video-card__title">{{ title }}</span>
          <div class="video-card__teaser">
            <h2 class="video-card__subtitle">
              {{ subtitle }}
            </h2>
            <p class="video-card__description">
              {{ description }}
            </p>
          </div>
        </template>
      </article>
    </router-link>
  </div>
</template>

<script>
import { truncate } from 'lodash';

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
      return truncate(this.item.description, {
        length: 100,
      });
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

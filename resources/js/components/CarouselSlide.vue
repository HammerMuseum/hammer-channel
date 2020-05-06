<template>
  <ContentLoader
    v-if="!item"
    :speed="2"
    :animate="true"
  />
  <div
    v-else
    class="carousel__slide"
  >
    <UiCard>
      <RouterLink
        :to="{name: 'video', params: {id: slug }}"
      >
        <div class="ui-card__thumbnail">
          <span class="ui-card__duration">{{ duration }}</span>
          <img
            :src="thumbnailUrl"
            class="ui-card__thumbnail-image"
          >
        </div>
        <article>
          <h2 class="ui-card__title">
            <span>{{ title }}</span>
          </h2>
          <p class="ui-card__description">
            {{ description }}
          </p>
        </article>
      </RouterLink>
    </UiCard>
  </div>
</template>

<script>
import { truncate } from 'lodash';
import { ContentLoader } from 'vue-content-loader';
import UiCard from './UiCard.vue';

export default {
  components: {
    ContentLoader,
    UiCard,
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
      return `/images/${this.item.thumbnailId}/medium`;
    },
    title() {
      return this.item.title;
    },
  },
};
</script>

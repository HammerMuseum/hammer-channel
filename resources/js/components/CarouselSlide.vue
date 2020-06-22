<template>
  <div
    class="carousel__slide"
  >
    <UiCard>
      <RouterLink
        :to="{name: 'video', params: {id: id, slug: slug }}"
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
          <span class="ui-card__date" v-if="showDate">
            {{ new Date(item.date_recorded) | dateFormat('MMM D, YYYY') }}
          </span>
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
import UiCard from './UiCard.vue';

export default {
  components: {
    UiCard,
  },
  props: {
    item: {
      type: Object,
      default() {
        return {};
      },
    },
    showDate: {
      type: Boolean,
      default: false,
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
    id() {
      return this.item.asset_id;
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

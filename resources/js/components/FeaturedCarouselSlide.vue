<template>
  <div class="carousel__slide carousel__slide--featured">
    <ui-card :extra-classes="['ui-card--featured']">
      <router-link
        :to="{name: 'video', params: {id: slug}}"
        class="featured-slide"
      >
        <div class="ui-card__thumbnail">
          <span class="ui-card__duration">{{ duration }}</span>
          <img
            class="ui-card__thumbnail-image"
            :src="thumbnailUrl"
          >
        </div>
        <article class="ui-card__info">
          <template v-if="quote">
            <q class="ui-card__quote">{{ quote }}</q>
            <div class="ui-card__teaser">
              <h2 class="ui-card__title">
                {{ title }}
              </h2>
            </div>
          </template>
          <template v-else>
            <span class="ui-card__title">{{ title }}</span>
            <div class="ui-card__teaser">
              <h2 class="ui-card__subtitle">
                {{ subtitle }}
              </h2>
              <p class="ui-card__description">
                {{ description }}
              </p>
            </div>
          </template>
        </article>
      </router-link>
    </ui-card>
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

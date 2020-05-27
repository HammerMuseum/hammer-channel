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
        :to="{name: 'video', params: {id: id, slug: slug }}"
      >
        <div class="ui-card__thumbnail">
          <span class="ui-card__duration">{{ duration }}</span>
          <VImg
            :classes="{ root: 'ui-card__thumbnail-image', placeholder: 'ui-card__thumbnail-image'}"
            :src="`${thumbnailUrl}/large`"
            width="320"
            height="180"
            :srcset="`
              ${thumbnailUrl}/smallest 320w,
              ${thumbnailUrl}/small 480w,
              ${thumbnailUrl}/medium 800w`"
            sizes="(max-width: 320px) 280px,
              (max-width: 480px) 440px,
              (max-width: 800px) 760px,
              1080px"
            :alt="title"
            background="#ddd"
            transition-duration="80"
          />
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
import { VImg } from 'vuetensils/src/components';
import UiCard from './UiCard.vue';

export default {
  components: {
    ContentLoader,
    UiCard,
    VImg,
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
    id() {
      return this.item.asset_id;
    },
    slug() {
      return this.item.title_slug;
    },
    thumbnailUrl() {
      return `/images/${this.item.thumbnailId}`;
    },
    title() {
      return this.item.title;
    },
  },
};
</script>

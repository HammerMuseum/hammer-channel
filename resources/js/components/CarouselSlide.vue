<template>
  <div
    class="carousel__slide"
  >
    <UiCard>
      <RouterLink
        :to="{name: 'video', params: {id: id, slug: slug }}"
      >
        <div class="ui-card__thumbnail">
          <img
            :src="thumbnailUrl"
            class="ui-card__thumbnail-image"
            alt=""
          >
        </div>
        <component :is="headingType" class="ui-card__title">
          <span>{{ title }}</span>
        </component>
        <span
          v-if="showDate"
          class="ui-card__date"
        >
          {{ new Date(item.date_recorded) | dateFormat('MMM D, YYYY') }}
        </span>
        <button @click="saveVideo(item)">
          Save video to watch later
        </button>
        <RichText
          :classes="['ui-card__description']"
          :text="item.description"
          :truncate="100"
        />
        <Duration :duration="duration" />
      </RouterLink>
    </UiCard>
  </div>
</template>

<script>
import Cookies from 'js-cookie';
import truncate from 'lodash/truncate';
import RichText from './RichText.vue';
import UiCard from './UiCard.vue';

export default {
  components: {
    RichText,
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
    headingType: {
      type: String,
      default: 'h2',
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
      return `/images/d/medium/${this.item.thumbnailId}.jpg`;
    },
    title() {
      return this.item.title;
    },
  },
  methods: {
    saveVideo(item) {
      // Get the current list of saved video IDs from the cookie
      const savedIds = Cookies.get('saved_video_ids') || '';

      // Split the list of IDs into an array and add the current ID
      const idArray = savedIds.split('|');
      idArray.push(item.asset_id.toString());

      // Use a Set to remove duplicates and convert back to an array
      const uniqueIds = Array.from(new Set(idArray));

      // Filter out any empty items in the array and join the remaining IDs
      // into a pipe-separated string
      const newIds = uniqueIds.filter((id) => id !== '').join('|');

      Cookies.set('saved_video_ids', newIds);
    },
  }
};
</script>

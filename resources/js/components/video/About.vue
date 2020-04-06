<template>
  <div>
    <div v-if="playlists">
      Part of:
      <span
        v-for="item in playlists"
        :key="item"
      >
        <RouterLink
          :to="{name: 'search', query: { in_playlists: item } }"
        >
          {{ item }}
        </RouterLink>
      </span>
    </div>
    <div v-if="people">
      Featuring:
      <span
        v-for="item in people"
        :key="item"
      >
        <RouterLink
          :to="{name: 'search', query: { speakers: item } }"
        >
          {{ item }}
        </RouterLink>
      </span>
    </div>

    <h3 class="video-meta__section-title">
      Description
    </h3>

    <div class="video-meta__description">
      <p>{{ description }}</p>
    </div>

    <div class="video-meta__date-recorded">
      Recorded: {{ new Date(dateRecorded) | dateFormat('dddd, DD MMMM, YYYY') }}
    </div>
  </div>
</template>

<script>
export default {
  name: 'About',
  props: {
    dateRecorded: {
      type: String,
      default: '',
    },
    description: {
      type: String,
      default: '',
    },
    people: {
      type: Array,
      default() {
        return [];
      },
    },
    playlists: {
      type: Array,
      default() {
        return [];
      },
    },
  },
};
</script>

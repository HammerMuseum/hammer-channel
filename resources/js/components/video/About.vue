<template>
  <div>
    <div v-if="playlists">
      Part of:
      <span
        v-for="item in playlists"
        :key="item"
      >
        <router-link
          :to="{name: 'search', query: { in_playlists: item } }"
        >
          {{ item }}
        </router-link>
      </span>
    </div>
    <div v-if="people">
      Featuring:
      <span
        v-for="item in people"
        :key="item"
      >
        <router-link
          :to="{name: 'search', query: { speakers: item } }"
        >
          {{ item }}
        </router-link>
      </span>
    </div>

    <h3 class="video-meta__section-title">
      Description
    </h3>

    <div class="video-meta__description">
      <p>{{ description }}</p>
    </div>

    <div class="video-meta__date-recorded">
      Recorded: {{ new Date(date) | dateFormat('dddd, DD MMMM, YYYY') }}
    </div>
  </div>
</template>

<script>
export default {
  name: 'About',
  props: {
    date: {
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

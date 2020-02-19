<template>
  <div
    scroll
  >
    <div class="video-meta__date-recorded">
      Recorded: {{ new Date(date) | dateFormat('dddd, DD MMMM, YYYY') }}
    </div>

    <h3 class="video-meta__section-title">
      Description
    </h3>
    <div class="video-meta__description">
      <p>{{ description }}</p>
    </div>

    <div v-if="programSeries">
      Part of:
      <router-link
        :to="{name: 'search', params: {params:`?facets=[1]program_series:${programSeries}`}}"
      >
        {{ programSeries }}
      </router-link>
    </div>
  </div>
</template>

<script>
export default {
  name: 'About',
  props: {
    date: String,
    description: String,
    keywords: Array,
    programSeries: String,
  },
};
</script>

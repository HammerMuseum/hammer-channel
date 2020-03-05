<template>
  <div class="result-grid">
    <div
      v-for="video in videos"
      class="result-item"
    >
      <span class="video__duration">{{ video['duration'] }}</span>
      <router-link
        :to="{name: 'video', params: {id: video.title_slug}}"
      >
        <img
          class="result-item__image"
          :src="`${ video.thumbnail_url }`"
        >
        <span class="result-item__title">{{ video['title'] }}</span>
      </router-link>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ResultGrid',
  props: {
    videos: Array,
  },
};
</script>

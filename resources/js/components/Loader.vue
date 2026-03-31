<template>
  <div class="loader">
    <ContentLoader
      class="content-loader"
      :height="153"
      :width="400"
      :speed="2"
      :animate="false"
      primary-color="#4d4b4b"
      secondary-color="#4d4b4b"
    >
      <rect
        x="2.5"
        y="1.4"
        rx="0"
        ry="0"
        width="180"
        height="150"
      />
      <rect
        x="190"
        y="12"
        rx="0"
        ry="0"
        width="180"
        height="16"
      />
      <rect
        x="190"
        y="32"
        rx="0"
        ry="0"
        width="180"
        height="16"
      />
      <rect
        x="190"
        y="108"
        rx="0"
        ry="0"
        width="180"
        height="8"
      />
      <rect
        x="190"
        y="120"
        rx="0"
        ry="0"
        width="180"
        height="8"
      />
      <rect
        x="190"
        y="132"
        rx="0"
        ry="0"
        width="180"
        height="8"
      />
    </ContentLoader>
  </div>
</template>

<script>
import { ContentLoader } from 'vue-content-loader';

export default {
  name: 'Loader',
  components: {
    ContentLoader,
  },
};
</script>

<style>
  .loader {
    width: 100%;
  }

  .content-loader {
    display: block;
    width: 100%;
    height: auto;
    max-height: 342px;
    max-width: 1280px;
    margin: 0 auto;
  }

  /* Ensure the SVG scales when vue-content-loader puts the class on a wrapper */
  .content-loader svg {
    width: 100%;
    height: auto;
    display: block;
  }
</style>

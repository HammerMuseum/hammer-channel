<template>
  <ContentLoader
    :width="800"
    :height="250"
    :animate="false"
    primary-color="#c6c6c6"
    secondary-color="#c6c6c6"
  >
    <rect
      x="425"
      y="3"
      rx="2"
      ry="2"
      width="361"
      height="26"
    />
    <rect
      x="425"
      y="44"
      rx="2"
      ry="2"
      width="361"
      height="26"
    />
    <rect
      x="6"
      y="2"
      rx="2"
      ry="2"
      width="400"
      height="192"
    />
    <rect
      x="425"
      y="83"
      rx="2"
      ry="2"
      width="361"
      height="26"
    />
    <rect
      x="425"
      y="124"
      rx="2"
      ry="2"
      width="361"
      height="26"
    />
  </ContentLoader>
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

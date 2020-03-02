<template>
  <router-view />
</template>
<script>
import mixin from '../mixins/getRouteData';

export default {
  mixins: [mixin],
  mounted() {
    console.log('Mounted');
  },
};
</script>

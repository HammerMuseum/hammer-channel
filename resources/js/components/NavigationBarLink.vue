<template>
  <a
    :href="processedHref"
    :class="['link', {'link--active': activeItem === item }]"
  >{{ item }}</a>
</template>

<script>
import { filterId, anchorLink } from '../filters';

export default {
  name: 'NavigationBarLink',
  props: {
    href: {
      type: Object,
      default() {
        return {};
      },
    },
    text: {
      type: String,
      default() {
        return '';
      },
    },
    classes: {
      type: Array,
      default() {
        return [];
      },
    },
  },
  computed: {
    processedHref() {
      return anchorLink(filterId(this.href));
    },
  },
};
</script>

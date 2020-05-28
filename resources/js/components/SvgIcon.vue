<template>
  <svg
    :aria-labelledby="id"
    role="img"
    :class="['icon', `icon--${name}`]"
  >
    <title
      :id="id"
      lang="en"
    >{{ title }}</title>
    <use :xlink:href="href" />
  </svg>
</template>

<script>
export default {
  name: 'SvgIcon',
  props: {
    name: String,
    title: String,
  },
  computed: {
    href() {
      return `/images/sprite.svg#sprite-${this.name}`;
    },
    id() {
      return `${this.name}-icon-title`;
    },
  },
};
</script>

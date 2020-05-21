<template>
  <svg
    :title="title"
    :class="classes"
  >
    <use :xlink:href="path" />
  </svg>
</template>

<script>
const spritePath = '/images/sprite.svg';
const spritePrefix = 'sprite-';

export default {
  props: {
    name: {
      type: String,
      required: true,
    },
    title: {
      type: String,
      required: true,
    },
    extraClasses: {
      type: Array,
      default() {
        return [];
      },
    },
  },
  computed: {
    classes() {
      return ['icon', ...this.extraClasses];
    },
    path() {
      return `${spritePath}#${spritePrefix}${this.name}`;
    },
  },
};
</script>
